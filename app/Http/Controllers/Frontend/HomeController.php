<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get featured courses (you can modify the logic as needed)
        $query = Course::with('category')
            ->where('status', true);

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        
        $featuredCourses = $query->latest()
            ->limit(6)
            ->get();

        if ($request->ajax()) {
            return view('frontend.partials.course-list', compact('featuredCourses'))->render();
        }

        $categories = Category::withCount('courses')->get();
        $studentCount = User::role('student')->count();

        return view('frontend.home', compact('featuredCourses', 'categories', 'studentCount'));
    }
}
