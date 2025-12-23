<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Category::select(['id', 'name', 'status']);

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
    return $row->status == 1
        ? '<span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>'
        : '<span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>';
})

                ->addColumn('action', function ($data) {
                    return view('backend.layouts.action', compact('data'))
                        ->with('module', 'categories')
                        ->with('module2', 'admin.categories');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.master.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.master.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:categories,name',
            'status' => 'required|in:0,1',
        ]);

        Category::create($validated);

        return response()->json([
            'success' => 'Category Added Successfully',
            'url'     => route('admin.categories.index'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('backend.master.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:categories,name,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return response()->json([
            'success' => 'Category Updated Successfully',
            'url'     => route('admin.categories.index'),
        ]);
    }
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.master.categories.view', compact('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => 'Category Deleted Successfully',
        ]);
    }
}
