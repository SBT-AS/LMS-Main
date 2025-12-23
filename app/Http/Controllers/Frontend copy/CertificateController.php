<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CertificateController extends Controller
{
    /**
     * Display the certificate for a specific course
     */
    public function show($id)
    {
        $user = auth()->user();
        $course = Course::findOrFail($id);

        // Check if user is enrolled in this course
        $enrollment = $user->enrolledCourses()->where('course_id', $id)->first();

        if (!$enrollment) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // We could check if status is 'completed' if we wanted to be strict
        // But the user requested "jo purchase kare", so we'll allow it for active/completed.
        
        $nameParts = explode(' ', $user->name);
        $displayName = $user->name;
        if (count($nameParts) > 1) {
            $lastName = array_pop($nameParts);
            $displayName = implode(' ', $nameParts) . ' ' . strtoupper(substr($lastName, 0, 1)) . '.';
        }

        $data = [
            'user_name' => $displayName,
            'course_name' => $course->title,
            'enrolled_at' => Carbon::parse($enrollment->pivot->enrolled_at)->format('F d, Y'),
            'date' => now()->format('F d, Y'),
            'certificate_id' => 'CERT-' . strtoupper(substr(md5($user->id . $course->id), 0, 8))
        ];

        return view('frontend.certificates.show', compact('data'));
    }

    /**
     * List all certificates for the user
     */
    public function index()
    {
        $user = auth()->user();
        $enrolledCourses = $user->enrolledCourses()->get();
        
        return view('frontend.certificates.index', compact('enrolledCourses'));
    }
}
