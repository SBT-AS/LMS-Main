<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    /**
     * Show course details and content
     */
    public function show($slug)
    {

        $course = Course::where('slug', $slug)->firstOrFail();
            
        $user = auth()->user();
        
        // Check if user can access this course
        $canAccess = false;
        $accessType = null;
        
        // Check if course is free
        if ($course->price == 0 || $course->price === null) {
            $canAccess = true;
            $accessType = 'free';
        }
        // Check if user is enrolled
        elseif ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            $canAccess = true;
            $accessType = 'enrolled';
        }
        
        // If user can't access, redirect to home with error
        if (!$canAccess) {
            return redirect()->route('frontend.home')
                ->with('error', 'You need to enroll in this course to access it.');
        }
        
        // Load course materials and quizzes
        $course->load(['materials', 'quizzes']);
        
        return view('frontend.courses.show', compact('course', 'accessType'));
    }
    
    /**
     * Enroll in a course
     */
    public function enroll($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $user = auth()->user();
        
        // Check if already enrolled
        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return redirect()->route('student.courses.show', $course->slug)
                ->with('info', 'You are already enrolled in this course.');
        }
        
        // Enroll the user
        $user->enrolledCourses()->attach($course->id, [
            'status' => 'active',
            'enrolled_at' => now()
        ]);
        
        return redirect()->route('student.courses.show', $course->slug)
            ->with('success', 'Successfully enrolled in the course!');
    }
}
