<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\CourseMaterial;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Http\Requests\Admin\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
   
    /* =======================
      COURSE LIST (DATATABLE)
    ========================*/
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Course::select('id', 'title', 'image', 'video', 'price', 'status', 'created_at', 'live_class', 'category_id')
                ->withCount(['materials', 'quizzes'])
                ->with('category');

            if ($request->status !== null && $request->status !== '') {
                $data->where('status', $request->status);
            }

            if ($request->category_id) {
                $data->where('category_id', $request->category_id);
            }

            if ($request->price === 'free') {
                $data->where('price', 0);
            } elseif ($request->price === 'paid') {
                $data->where('price', '>', 0);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function($row){
                    return '<input type="checkbox" name="course_checkbox[]" class="course_checkbox" value="'.$row->id.'" data-id="'.$row->id.'">';
                })
                ->addColumn('category', function($row){
                    return $row->category ? $row->category->name : 'N/A';
                })


                ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))

                ->addColumn('action', fn($row) =>
                    view('backend.layouts.action',[
                        'data'=>$row,
                        'module'=>'courses',
                        'module2'=>'admin.courses'
                    ])->render()
                )

                ->rawColumns(['checkbox', 'image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.master.courses.index');
    }

    public function create()
    {
        $categories = Category::all();
        return view('backend.master.courses.create', compact('categories'));
    }

    /* =======================
        STORE COURSE
    ========================*/
    public function store(CourseRequest $request)
    {
    
        /* =======================
            STORE DATA
        ========================*/
        try {
            DB::transaction(function () use ($request) {
    
                $image = null;
                if ($request->hasFile('image')) {
                    $image = time().'_'.Str::random(8).'.'.$request->image->extension();
                    $request->image->storeAs('courses', $image, 'public');
                }
    
                $video = null;
                if ($request->hasFile('video')) {
                    $video = time().'_'.Str::random(8).'.'.$request->video->extension();
                    $request->video->storeAs('courses', $video, 'public');
                }
    
                $course = Course::create([
                    'title'       => $request->title,
                    'slug'        => Str::slug($request->title).'-'.time(),
                    'description' => $request->description,
                    'price'       => $request->price,
                    'image'       => $image,
                    'video'       => $video,
                    'status'      => $request->status,
                    'category_id' => $request->category_id,
                    'live_class'  => $request->live_class ?? 0,
                    'live_class_link' => $request->live_class_link,
                ]);
    
                /* ===== MATERIALS ===== */
                if ($request->include_material == 1 && $request->has('materials')) {
                    foreach ($request->materials as $key => $mat) {
                        $path = null;
                        
                        // Explicitly get the file from request to handle nested array uploads correctly
                        $file = $request->file("materials.$key.file");

                        if ($mat['type'] === 'file' && $file) {
                            $path = $file->store('course-materials', 'public');
                        }
    
                        CourseMaterial::create([
                            'course_id'     => $course->id,
                            'title'         => $mat['title'],
                            'material_type' => $mat['material_type'] ?? 'video',
                            'type'          => $mat['type'],
                            'file_path'     => $path,
                            'url'           => $mat['type'] === 'url' ? ($mat['url'] ?? null) : null,
                        ]);
                    }
                }
    
                /* ===== QUIZZES ===== */
                if ($request->include_quiz == 1) {
                    foreach ($request->quizzes as $qz) {
    
                        $quiz = Quiz::create([
                            'course_id'   => $course->id,
                            'title'       => $qz['title'],
                            'duration'    => $qz['duration'],
                            'instructions'=> $qz['instructions'] ?? null,
                        ]);
    
                        foreach ($qz['questions'] as $qs) {
                            QuizQuestion::create([
                                'quiz_id'        => $quiz->id,
                                'question'       => $qs['text'],
                                'option1'        => $qs['options'][1],
                                'option2'        => $qs['options'][2],
                                'option3'        => $qs['options'][3],
                                'option4'        => $qs['options'][4],
                                'correct_answer'=> $qs['correct'],
                                'explanation'   => $qs['explanation'] ?? null,
                            ]);
                        }
                    }
                }
            });
    
            return response()->json([
                'success' => 'Course Added Successfully',
                'url'     => route('admin.courses.index'),
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    

    public function edit(Course $course)
    {
        $categories = Category::all();
        $course->load('materials','quizzes.questions');
        return view('backend.master.courses.edit',compact('course', 'categories'));
    }

    /* =======================
        UPDATE COURSE
    ========================*/
    /* =======================
    UPDATE COURSE
=======================*/
public function update(CourseRequest $request, Course $course)
{
    /* =======================
        UPDATE DATA
    ========================*/
    try {
        DB::transaction(function () use ($request, $course) {

            // Update image
            if ($request->hasFile('image')) {
                if ($course->image) {
                    Storage::disk('public')->delete('courses/'.$course->image);
                }
                $course->image = time().'_'.Str::random(8).'.'.$request->image->extension();
                $request->image->storeAs('courses', $course->image, 'public');
            }

            // Update video
            if ($request->hasFile('video')) {
                if ($course->video) {
                    Storage::disk('public')->delete('courses/'.$course->video);
                }
                $course->video = time().'_'.Str::random(8).'.'.$request->video->extension();
                $request->video->storeAs('courses', $course->video, 'public');
            }

            // Update course
            $course->update([
                'title'       => $request->title,
                'slug'        => Str::slug($request->title).'-'.time(),
                'description' => $request->description,
                'price'       => $request->price,
                'category_id' => $request->category_id,
                'status'      => $request->status,
                'live_class'  => $request->live_class ?? 0,
                'live_class_link' => $request->live_class_link,
            ]);

            /* ===== MATERIALS ===== */
            CourseMaterial::where('course_id', $course->id)->delete();

            if ($request->include_material == 1 && $request->has('materials')) {
                foreach ($request->materials as $key => $mat) {
                    $path = null;

                    // Handle file upload or existing file
                    $file = $request->file("materials.$key.file");
                    
                    if ($mat['type'] === 'file') {
                        if ($file) {
                            $path = $file->store('course-materials', 'public');
                        } elseif (isset($mat['existing_file'])) {
                            $path = $mat['existing_file'];
                        }
                    }

                    CourseMaterial::create([
                        'course_id'     => $course->id,
                        'title'         => $mat['title'],
                        'material_type' => $mat['material_type'] ?? 'video',
                        'type'          => $mat['type'],
                        'file_path'     => $path,
                        'url'           => $mat['type'] === 'url' ? ($mat['url'] ?? null) : null,
                    ]);
                }
            }

            /* ===== QUIZZES ===== */
            QuizQuestion::whereIn(
                'quiz_id',
                Quiz::where('course_id', $course->id)->pluck('id')
            )->delete();

            Quiz::where('course_id', $course->id)->delete();

            if ($request->include_quiz == 1) {
                foreach ($request->quizzes as $qz) {

                    $quiz = Quiz::create([
                        'course_id'   => $course->id,
                        'title'       => $qz['title'],
                        'duration'    => $qz['duration'],
                        'instructions'=> $qz['instructions'] ?? null,
                    ]);

                    foreach ($qz['questions'] as $qs) {
                        QuizQuestion::create([
                            'quiz_id'        => $quiz->id,
                            'question'       => $qs['text'],
                            'option1'        => $qs['options'][1],
                            'option2'        => $qs['options'][2],
                            'option3'        => $qs['options'][3],
                            'option4'        => $qs['options'][4],
                            'correct_answer'=> $qs['correct'] ?? 1,
                            'explanation'   => $qs['explanation'] ?? null,
                        ]);
                    }
                }
            }
        });

        return response()->json([
            'success' => 'Course updated successfully',
            'url'     => route('admin.courses.index'),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}


    public function show(Course $course)
    {
        $course->load('materials','quizzes.questions');
        return view('backend.master.courses.view',compact('course'));
    }

    public function destroy(Course $course)
    {
        if ($course->image) {
            Storage::disk('public')->delete('courses/'.$course->image);
        }
        if ($course->video) {
            Storage::disk('public')->delete('courses/'.$course->video);
        }
        $course->delete();
        return response()->json([
            'success' => 'Course deleted successfully',
            'url'     => route('admin.courses.index'),
        ]);
    }
    // CourseController.php में नया method add करें:

/* =======================
    BULK ACTIONS
=======================*/
public function bulkAction(Request $request)
{
    $request->validate([
        'action' => 'required|in:activate,deactivate,delete',
        'ids' => 'required|array',
        'ids.*' => 'exists:courses,id'
    ]);

    try {
        DB::beginTransaction();

        switch ($request->action) {
            case 'activate':
                Course::whereIn('id', $request->ids)->update(['status' => 1]);
                $message = 'Selected courses activated successfully';
                break;

            case 'deactivate':
                Course::whereIn('id', $request->ids)->update(['status' => 0]);
                $message = 'Selected courses deactivated successfully';
                break;

            case 'delete':
                $courses = Course::whereIn('id', $request->ids)->get();
                foreach ($courses as $course) {
                    if ($course->image) {
                        Storage::disk('public')->delete('courses/'.$course->image);
                    }
                    if ($course->video) {
                        Storage::disk('public')->delete('courses/'.$course->video);
                    }
                    $course->delete();
                }
                $message = 'Selected courses deleted successfully';
                break;
        }

        DB::commit();
        return response()->json([
            'success' => true,
            'message' => $message,
            'url'     => route('admin.courses.index'),
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}
}
