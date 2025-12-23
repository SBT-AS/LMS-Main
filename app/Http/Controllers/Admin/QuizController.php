<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes for a course
     */
    public function index(Course $course)
    {
        $quizzes = $course->quizzes()->with('questions')->get();
        return view('backend.quizzes.index', compact('course', 'quizzes'));
    }

    /**
     * Show the form for creating a new quiz
     */
    public function create(Course $course)
    {
        return view('backend.quizzes.create', compact('course'));
    }

    /**
     * Store a newly created quiz
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'instructions' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.option1' => 'required|string',
            'questions.*.option2' => 'required|string',
            'questions.*.option3' => 'required|string',
            'questions.*.option4' => 'required|string',
            'questions.*.correct_answer' => 'required|integer|min:1|max:4',
            'questions.*.explanation' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create quiz
            $quiz = $course->quizzes()->create([
                'title' => $validated['title'],
                'duration' => $validated['duration'],
                'instructions' => $validated['instructions'],
            ]);

            // Create questions
            foreach ($validated['questions'] as $questionData) {
                $quiz->questions()->create($questionData);
            }

            DB::commit();

         return response()->json([
            'success' => 'Quiz Created Successfully',
            'url'     => route('admin.courses.quizzes.index', $course),
        ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create quiz: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified quiz
     */
    public function edit(Course $course, Quiz $quiz)
    {
        $quiz->load('questions');
        return view('backend.quizzes.edit', compact('course', 'quiz'));
    }

    /**
     * Update the specified quiz
     */
    public function update(Request $request, Course $course, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'instructions' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:quiz_questions,id',
            'questions.*.question' => 'required|string',
            'questions.*.option1' => 'required|string',
            'questions.*.option2' => 'required|string',
            'questions.*.option3' => 'required|string',
            'questions.*.option4' => 'required|string',
            'questions.*.correct_answer' => 'required|integer|min:1|max:4',
            'questions.*.explanation' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Update quiz
            $quiz->update([
                'title' => $validated['title'],
                'duration' => $validated['duration'],
                'instructions' => $validated['instructions'],
            ]);

            // Keep track of question IDs to preserve
            $questionIds = [];

            // Update or create questions
            foreach ($validated['questions'] as $questionData) {
                if (isset($questionData['id'])) {
                    // Update existing question
                    $question = QuizQuestion::find($questionData['id']);
                    if ($question && $question->quiz_id == $quiz->id) {
                        $question->update($questionData);
                        $questionIds[] = $question->id;
                    }
                } else {
                    // Create new question
                    $question = $quiz->questions()->create($questionData);
                    $questionIds[] = $question->id;
                }
            }

            // Delete questions that were removed
            $quiz->questions()->whereNotIn('id', $questionIds)->delete();

            DB::commit();

          return response()->json([
            'success' => 'Quiz Updated Successfully',
            'url'     => route('admin.courses.quizzes.index', $course),
        ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update quiz: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified quiz
     */
    public function destroy(Course $course, Quiz $quiz)
    {
        try {
            $quiz->delete();
          return response()->json([
            'success' => 'Quiz Deleted Successfully',
            'url'     => route('admin.courses.quizzes.index', $course),
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete quiz: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show quiz attempts/results
     */
    public function showResults(Course $course, Quiz $quiz)
    {
        $attempts = $quiz->attempts()
            ->with('user')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('backend.quizzes.results', compact('course', 'quiz', 'attempts'));
    }
}
