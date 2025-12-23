@extends('backend.layouts.master')

@section('title', 'Course Quizzes')
@section('header_title', 'Quiz Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quizzes for: {{ $course->title }}</h1>
            <p class="text-gray-500 text-sm mt-1">Manage quizzes for this course</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.courses.index') }}"
               class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Back to Courses
            </a>
            <a href="{{ route('admin.courses.quizzes.create', $course) }}"
               class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg text-sm font-semibold transition-all flex items-center gap-2">
                <i class="bi bi-plus-lg"></i> Create Quiz
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Quizzes List -->
    @if($quizzes->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <i class="bi bi-question-circle text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Quizzes Yet</h3>
            <p class="text-gray-500 mb-6">Create your first quiz for this course</p>
            <a href="{{ route('admin.courses.quizzes.create', $course) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-semibold transition-all">
                <i class="bi bi-plus-lg"></i> Create Quiz
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quizzes as $quiz)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4">
                        <h3 class="text-white font-semibold text-lg">{{ $quiz->title }}</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="bi bi-clock text-indigo-600"></i>
                                <span>{{ $quiz->duration }} minutes</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="bi bi-question-circle text-indigo-600"></i>
                                <span>{{ $quiz->questions->count() }} questions</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="bi bi-person-check text-indigo-600"></i>
                                <span>{{ $quiz->attempts->count() }} attempts</span>
                            </div>
                        </div>

                        @if($quiz->instructions)
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $quiz->instructions }}</p>
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('admin.courses.quizzes.edit', [$course, $quiz]) }}"
                               class="flex-1 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 text-sm font-semibold text-center transition-all">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('admin.courses.quizzes.results', [$course, $quiz]) }}"
                               class="flex-1 px-4 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 text-sm font-semibold text-center transition-all">
                                <i class="bi bi-bar-chart"></i> Results
                            </a>
                            @can('quizzes.delete')
                            <button type="button" 
                                    class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 text-sm font-semibold transition-all delete-btn"
                                    data-url="{{ route('admin.courses.quizzes.destroy', [$course, $quiz]) }}"
                                    title="Delete Quiz">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        AjaxCrud.init();
    });
</script>
@endpush

