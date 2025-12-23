<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch enrolled courses
        $enrolledCourses = $user->enrolledCourses()->get();

        // Also fetch free courses if needed, or maybe just enrolled ones.
        // The user asked for "Free courses are visible" in previous turn, now wants "Enrolled courses" in dashboard.
        // A balanced approach: Show enrolled courses at top, and suggested/free courses below or in a separate tab.
        // For strict compliance with "Student Dashboard: Show Enrolled courses", I'll pass enrolledCourses.
        
        $freeCourses = Course::where('price', 0)->orWhereNull('price')->get();
        $enrolledCourseIds = $enrolledCourses->pluck('id')->toArray();

        return view('frontend.dashboard', compact('enrolledCourses', 'freeCourses', 'enrolledCourseIds'));
    }
}
