<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentQuizController extends Controller
{
    public function index(Course $course)
    {
        $quizzes = $course->quizzes()->get();
        $user = Auth::user();

        // Get user's attempts for each quiz
        foreach ($quizzes as $quiz) {
            $quiz->userAttempts = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->latest()
                ->get();
            
            $quiz->best_score = $quiz->userAttempts->max('score');
        }

        return view('frontend.quizzes.index', compact('course', 'quizzes'));
    }

    public function start(Request $request, Course $course, Quiz $quiz)
    {
        $user = Auth::user();

        // Check if user is enrolled in the course
        if (!$user->courses->contains($course->id)) {
            return redirect()->back()->with('error', 'You must be enrolled in this course to take quizzes.');
        }

        // Create a new quiz attempt
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'total_questions' => $quiz->questions()->count(),
            'started_at' => now(),
        ]);

        return redirect()->route('student.quizzes.take', [$course->id, $quiz->id, $attempt->id]);
    }

    public function take(Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        // Ensure the attempt belongs to the logged-in user
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if already completed
        if ($attempt->completed_at) {
            return redirect()->route('student.quizzes.result', [$course->id, $quiz->id, $attempt->id]);
        }

        $questions = $quiz->questions()->get();

        return view('frontend.quizzes.take', compact('course', 'quiz', 'attempt', 'questions'));
    }

    public function submit(Request $request, Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        // Ensure the attempt belongs to the logged-in user
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $questions = $quiz->questions;
        $correctCount = 0;
        $totalQuestions = $questions->count();

        DB::transaction(function () use ($request, $attempt, $questions, &$correctCount, $totalQuestions) {
            foreach ($questions as $question) {
                $selected = $request->input('question_' . $question->id);
                $isCorrect = ($selected == $question->correct_answer);
                
                if ($isCorrect) {
                    $correctCount++;
                }

                QuizAnswer::updateOrCreate(
                    [
                        'quiz_attempt_id' => $attempt->id,
                        'quiz_question_id' => $question->id,
                    ],
                    [
                        'selected_answer' => $selected ?? 0,
                        'is_correct' => $isCorrect,
                    ]
                );
            }

            $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

            $attempt->update([
                'completed_at' => now(),
                'score' => $score,
                'total_questions' => $totalQuestions
            ]);
        });

        return redirect()->route('student.quizzes.result', [$course->id, $quiz->id, $attempt->id])
            ->with('success', 'Quiz submitted successfully!');
    }

    public function result(Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        // Ensure the attempt belongs to the logged-in user
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load(['answers.question']);

        return view('frontend.quizzes.result', compact('course', 'quiz', 'attempt'));
    }
}
