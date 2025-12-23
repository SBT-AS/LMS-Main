<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get enrolled courses
        $enrolledCourses = $user->courses()->with('category')->get();
        $enrolledCourseIds = $enrolledCourses->pluck('id')->toArray();
        
        // Get certificates
        $certificates = $user->certificates()->with('course')->get();
        
        // Get quiz attempts
        $quizAttempts = $user->quizAttempts()
            ->with(['quiz', 'quiz.course'])
            ->latest()
            ->limit(5)
            ->get();

        // Get some recommended free courses
        $freeCourses = \App\Models\Course::where('price', 0)
            ->where('status', true)
            ->limit(3)
            ->get();

        return view('frontend.dashboard', compact('enrolledCourses', 'certificates', 'quizAttempts', 'freeCourses', 'enrolledCourseIds'));
    }
}
