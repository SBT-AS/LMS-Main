<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with courses
     */
    public function index(Request $request)
    {
        $query = Course::where('status', 1)
            ->withCount(['videoMaterials', 'pdfMaterials', 'imageMaterials', 'linkMaterials']);

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Search by title
        if ($request->has('search') && $request->search != '') {
                $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $courses = $query->latest()
            ->paginate(12) // Use pagination for better performance if many courses
            ->withQueryString();

        $categories = Category::where('status', 1)->get();

        $enrolledCourseIds = [];
        if (auth()->check()) {
            $enrolledCourseIds = auth()->user()->enrolledCourses()->pluck('course_id')->toArray();
        }

        return view('frontend.home', compact('courses', 'enrolledCourseIds', 'categories'));
    }
}
