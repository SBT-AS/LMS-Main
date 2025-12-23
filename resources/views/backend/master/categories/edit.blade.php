@extends('backend.layouts.master')

@section('title', 'Edit Category')
@section('header_title', 'Edit Category')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Edit Category</h2>
            <a href="{{ route('admin.categories.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                <i class="bi bi-arrow-left mr-1"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" id="crudForm" class="p-6">
            @csrf
            @method('PUT')
            <div class="mb-8">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ $category->name }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                       placeholder="Enter Category Name">
            </div>
          <!--stetus-->
          <div class="mb-8">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
            <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>



            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <button type="submit" id="saveBtn" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors shadow-md shadow-indigo-200">
                 <i class="bi bi-save mr-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')

@endpush
