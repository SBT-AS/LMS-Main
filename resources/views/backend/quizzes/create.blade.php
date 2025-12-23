@extends('backend.layouts.master')

@section('title', 'Create Quiz')
@section('header_title', 'Quiz Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Quiz for {{ $course->title }}</h1>
            <p class="text-gray-500 text-sm mt-1">Create a quiz with multiple choice questions</p>
        </div>
        <a href="{{ route('admin.courses.quizzes.index', $course) }}"
           class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Quizzes
        </a>
    </div>

    <form id="crudForm" action="{{ route('admin.courses.quizzes.store', $course) }}" method="POST">
        @csrf

        <!-- Quiz Details Section -->
        <div class="form-section">
            <div class="section-header">
                <i class="bi bi-info-circle"></i>
                <div>
                    <h3 class="font-semibold text-lg">Quiz Details</h3>
                    <p class="text-sm opacity-90">Basic information about the quiz</p>
                </div>
            </div>
            <div class="section-body">
                <!-- Title -->
                <div class="input-group">
                    <label class="input-label">
                        Quiz Title <span class="required">*</span>
                    </label>
                    <input type="text" name="title" class="input-field" 
                           placeholder="Enter quiz title"
                           value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration & Instructions Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Duration -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Duration (Minutes) <span class="required">*</span>
                        </label>
                        <input type="number" name="duration" class="input-field" 
                               placeholder="30" min="1"
                               value="{{ old('duration', 30) }}" required>
                        @error('duration')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Instructions -->
                <div class="input-group">
                    <label class="input-label">
                        Instructions
                    </label>
                    <textarea name="instructions" rows="3" class="input-field" 
                              placeholder="Enter instructions for students...">{{ old('instructions') }}</textarea>
                    @error('instructions')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="form-section">
            <div class="section-header">
                <i class="bi bi-question-circle"></i>
                <div>
                    <h3 class="font-semibold text-lg">Questions</h3>
                    <p class="text-sm opacity-90">Add multiple choice questions</p>
                </div>
            </div>
            <div class="section-body">
                <div id="questionsContainer">
                    <!-- Questions will be added here dynamically -->
                </div>
                
                <button type="button" class="add-btn mt-4" onclick="addQuestion()">
                    <i class="bi bi-plus-circle"></i>
                    Add Question
                </button>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="flex justify-end gap-4 mb-8">
            <a href="{{ route('admin.courses.quizzes.index', $course) }}" 
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                Cancel
            </a>
            <button id="saveBtn"type="submit" class="submit-btn flex items-center gap-2">
                <i class="bi bi-check-lg"></i>
                Create Quiz
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/css/quizzes.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('backend/js/quizzes.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            QuizEditor.init(0);
        });
    </script>
@endpush

