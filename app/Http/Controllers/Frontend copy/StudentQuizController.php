<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentQuizController extends Controller
{
    /**
     * Show quiz list for a course
     */
    public function index(Course $course)
    {
        // Check if user is enrolled
      

        $quizzes = $course->quizzes()->withCount('questions')->get();
        
        // Get user's attempts for each quiz
        foreach ($quizzes as $quiz) {
            $quiz->userAttempts = $quiz->attempts()
                ->where('user_id', auth()->id())
                ->orderBy('completed_at', 'desc')
                ->get();
        }

        return view('frontend.quizzes.index', compact('course', 'quizzes'));
    }

    /**
     * Start a quiz attempt
     */
    public function start(Course $course, Quiz $quiz)
    {
       

        // Check if there's an incomplete attempt
        $incompleteAttempt = $quiz->attempts()
            ->where('user_id', auth()->id())
            ->whereNull('completed_at')
            ->first();

        if ($incompleteAttempt) {
            return redirect()->route('student.quizzes.take', [$course, $quiz, $incompleteAttempt]);
        }

        // Create new attempt
        $attempt = $quiz->attempts()->create([
            'user_id' => auth()->id(),
            'started_at' => Carbon::now(),
            'total_questions' => $quiz->questions()->count(),
        ]);

        return redirect()->route('student.quizzes.take', [$course, $quiz, $attempt]);
    }

    /**
     * Take/Continue quiz
     */
    public function take(Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        // Check if user is enrolled
       

        // Check if attempt belongs to current user
        if ($attempt->user_id !== auth()->id()) {
            abort(403, 'This quiz attempt does not belong to you.');
        }

        // Check if already completed
        if ($attempt->isCompleted()) {
            return redirect()->route('student.quizzes.result', [$course, $quiz, $attempt]);
        }

        // Load quiz with questions
        $quiz->load('questions');

        // Load existing answers
        $existingAnswers = $attempt->answers()->pluck('selected_answer', 'quiz_question_id')->toArray();

        return view('frontend.quizzes.take', compact('course', 'quiz', 'attempt', 'existingAnswers'));
    }

    /**
     * Submit quiz answer (AJAX)
     */
    public function submitAnswer(Request $request, Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'answer' => 'required|integer|min:1|max:4',
        ]);

        // Check if attempt belongs to current user
        if ($attempt->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if already completed
        if ($attempt->isCompleted()) {
            return response()->json(['error' => 'Quiz already completed'], 400);
        }

        $question = $quiz->questions()->findOrFail($validated['question_id']);
        
        // Check if answer already exists
        $answer = QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $question->id,
            ],
            [
                'selected_answer' => $validated['answer'],
                'is_correct' => $question->correct_answer == $validated['answer'],
            ]
        );

        return response()->json([
            'success' => true,
            'is_correct' => $answer->is_correct,
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
        ]);
    }

    /**
     * Submit entire quiz
     */
    public function submit(Request $request, Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        // Check if attempt belongs to current user
      

        // Check if already completed
        if ($attempt->isCompleted()) {
            return redirect()->route('student.quizzes.result', [$course, $quiz, $attempt]);
        }

        DB::beginTransaction();
        try {
            // Calculate score
            $correctAnswers = $attempt->answers()->where('is_correct', true)->count();
            
            // Update attempt
            $attempt->update([
                'score' => $correctAnswers,
                'completed_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()
                ->route('student.quizzes.result', [$course, $quiz, $attempt])
                ->with('success', 'Quiz submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to submit quiz: ' . $e->getMessage());
        }
    }

    /**
     * Show quiz result
     */
    public function result(Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        // Check if attempt belongs to current user
       

        // Check if completed
        if (!$attempt->isCompleted()) {
            return redirect()->route('student.quizzes.take', [$course, $quiz, $attempt]);
        }

        // Load relationships
        $attempt->load(['answers.question', 'quiz.questions']);

        return view('frontend.quizzes.result', compact('course', 'quiz', 'attempt'));
    }
}
