<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured courses (you can modify the logic as needed)
        $featuredCourses = Course::with('category')
            ->where('status', true)
            ->latest()
            ->limit(6)
            ->get();

        $categories = Category::withCount('courses')->get();

        return view('frontend.home', compact('featuredCourses', 'categories'));
    }
}
