<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    public function show($slug)
    {
        $course = Course::with(['category', 'materials', 'quizzes'])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = Auth::user();
        $isEnrolled = $user ? $user->courses->contains($course->id) : false;

        return view('frontend.course-detail', compact('course', 'isEnrolled'));
    }

    public function enroll(Request $request, $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Check if already enrolled
        if ($user->courses->contains($course->id)) {
            return redirect()->back()->with('info', 'You are already enrolled in this course.');
        }

        // Enroll the user
        $user->courses()->attach($course->id, [
            'enrolled_at' => now(),
            'status' => 'active'
        ]);

        return redirect()->route('student.courses.classroom', $slug)
            ->with('success', 'Successfully enrolled in the course!');
    }

    public function classroom(Request $request, $slug)
    {
        $course = Course::with(['materials', 'quizzes'])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = Auth::user();
        
        // Ensure user is enrolled
        if (!$user->courses->contains($course->id)) {
            return redirect()->route('student.courses.show', $slug)
                ->with('error', 'Please enroll in this course to access the classroom.');
        }

        $materials = $course->materials;
        $currentMaterial = null;

        if ($request->has('lesson')) {
            $currentMaterial = $materials->where('id', $request->lesson)->first();
        }

        if (!$currentMaterial) {
            $currentMaterial = $materials->first();
        }

        // Get adjacent materials for navigation
        $previousMaterial = $materials->where('id', '<', $currentMaterial->id)->last();
        $nextMaterial = $materials->where('id', '>', $currentMaterial->id)->first();

        // Calculate progress percentage
        $totalMaterials = $materials->count();
        $currentMaterialIndex = $materials->values()->search(fn($m) => $m->id === $currentMaterial->id);
        $progress = $totalMaterials > 0 ? round((($currentMaterialIndex + 1) / $totalMaterials) * 100) : 0;

        return view('frontend.classroom', compact('course', 'materials', 'currentMaterial', 'previousMaterial', 'nextMaterial', 'progress'));
    }

    public function finish($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Check if user is enrolled
        if (!$user->courses->contains($course->id)) {
            return redirect()->route('student.courses.show', $slug);
        }

        // Check if already has certificate
        $existing = \App\Models\Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$existing) {
            \App\Models\Certificate::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'certificate_number' => 'CERT-' . strtoupper(str_shuffle(substr(md5(time() . $user->id), 0, 8))),
                'issued_at' => now()
            ]);

            // Update course status in pivot table
            $user->courses()->updateExistingPivot($course->id, ['status' => 'completed']);

            return redirect()->route('student.dashboard')->with('success', 'Congratulations! You have successfully completed "' . $course->title . '" and earned your certificate.');
        }

        return redirect()->route('student.dashboard')->with('info', 'You have already completed this course.');
    }
}
