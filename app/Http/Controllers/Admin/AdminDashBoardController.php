<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashBoardController extends Controller
{
    public function index()
    {
        $data = [
            'total_students' => User::role('student')->count(),
            'total_courses' => Course::count(),
            'active_courses' => Course::where('status', 1)->count(),
            'total_enrollments' => DB::table('course_user')->count(),
            'total_revenue' => Course::join('course_user', 'courses.id', '=', 'course_user.course_id')
                                ->sum('courses.price'),
            'recent_students' => User::role('student')->latest()->take(5)->get(),
            'recent_courses' => Course::latest()->take(5)->get(),
            'recent_enrollments' => DB::table('course_user')
                                    ->join('users', 'course_user.user_id', '=', 'users.id')
                                    ->join('courses', 'course_user.course_id', '=', 'courses.id')
                                    ->select('users.name as student_name', 'courses.title as course_title', 'course_user.created_at')
                                    ->latest('course_user.created_at')
                                    ->take(5)
                                    ->get()
        ];

        return view('backend.dashboard', $data);
    }
}
