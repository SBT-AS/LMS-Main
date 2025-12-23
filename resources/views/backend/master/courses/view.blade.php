@extends('backend.layouts.master')

@section('title', 'View Course')
@section('header_title', 'Course Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/css/courses.css') }}">
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="detail-card">
        <div class="detail-header">
            <i class="bi bi-book"></i>
            <h2>{{ $course->title }}</h2>
        </div>
        
        <div class="detail-body">
            <!-- Course Image -->
            @if($course->image)
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-image"></i>
                    Course Thumbnail
                </div>
                <div class="detail-value">
                    <img src="{{ asset('storage/courses/' . $course->image) }}" 
                         alt="{{ $course->title }}" 
                         class="course-image">
                </div>
            </div>
            @endif
            
            <!-- Course Video -->
            @if($course->video)
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-film"></i>
                    Preview Video
                </div>
                <div class="detail-value">
                    <video src="{{ asset('storage/courses/' . $course->video) }}" 
                           controls
                           class="course-image" style="max-height: 250px; width: auto;">
                    </video>
                </div>
            </div>
            @endif
            
            <!-- Title -->
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-card-heading"></i>
                    Title
                </div>
                <div class="detail-value">
                    <strong>{{ $course->title }}</strong>
                </div>
            </div>
            
            <!-- Description -->
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-text-paragraph"></i>
                    Description
                </div>
                <div class="detail-value">
                    @if($course->description)
                        <div class="description-box">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    @else
                        <span class="text-gray-400 italic">No description provided</span>
                    @endif
                </div>
            </div>
            <!-- Category -->
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-card-heading"></i>
                    Category
                </div>
                <div class="detail-value">
                    <strong>{{ $course->category->name }}</strong>
                </div>
            </div>
            <!-- Price -->
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-currency-rupee"></i>
                    Price
                </div>
                <div class="detail-value">
                    <div class="price-display">
                        <span class="currency">₹</span>
                        {{ number_format($course->price, 2) }}
                        @if($course->price == 0)
                            <span class="ml-2 text-green-600 font-medium">(Free Course)</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Live Class -->
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-camera-video"></i>
                    Live Class
                </div>
                <div class="detail-value">
                    @if($course->live_class)
                        <span class="live-badge live-yes">
                            <i class="bi bi-check-circle-fill"></i>
                            Yes - Live Classes Available
                        </span>
                    @else
                        <span class="live-badge live-no">
                            <i class="bi bi-x-circle-fill"></i>
                            No - Pre-recorded Only
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Status -->
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-toggle-on"></i>
                    Status
                </div>
                <div class="detail-value">
                    @if($course->status)
                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle-fill"></i>
                            Active
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            <i class="bi bi-x-circle-fill"></i>
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            <!-- Course Features -->
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-collection"></i>
                    Course Features
                </div>
                <div class="detail-value">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Study Materials -->
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                <i class="bi bi-file-earmark-text text-blue-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Study Materials</p>
                                <p class="text-xs text-gray-500">
                                    {{ $course->materials->count() }} materials
                                </p>
                            </div>
                        </div>
                        
                        <!-- Quizzes -->
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                                <i class="bi bi-question-circle text-green-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Quizzes</p>
                                <p class="text-xs text-gray-500">
                                    {{ $course->quizzes->count() }} quizzes
                                </p>
                            </div>
                        </div>
                        
                        <!-- Total Questions -->
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                                <i class="bi bi-list-task text-purple-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Total Questions</p>
                                <p class="text-xs text-gray-500">
                                    {{ $course->quizzes->sum(function($quiz) { return $quiz->questions->count(); }) }} questions
                                </p>
                            </div>
                        </div>
                        
                        <!-- Total Duration -->
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center">
                                <i class="bi bi-clock text-orange-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Quiz Duration</p>
                                <p class="text-xs text-gray-500">
                                    {{ $course->quizzes->sum('duration') }} minutes total
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Study Materials Section -->
            @if($course->materials->count() > 0)
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-file-earmark-text"></i>
                    Study Materials
                </div>
                <div class="detail-value">
                    <div class="space-y-3">
                        @foreach($course->materials as $material)
                        <div class="material-item">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    @if($material->material_type == 'video')
                                        <i class="bi bi-play-circle text-blue-500 text-xl"></i>
                                    @elseif($material->material_type == 'pdf')
                                        <i class="bi bi-file-pdf text-red-500 text-xl"></i>
                                    @elseif($material->material_type == 'image')
                                        <i class="bi bi-image text-green-500 text-xl"></i>
                                    @else
                                        <i class="bi bi-file-earmark text-gray-500 text-xl"></i>
                                    @endif
                                    <div>
                                        <p class="font-medium">{{ $material->title }}</p>
                                        <p class="text-xs text-gray-500">
                                            <span class="capitalize font-semibold text-indigo-600">{{ $material->material_type }}</span>
                                            • {{ $material->type == 'file' ? 'File' : 'External Link' }}
                                            @if($material->type == 'file')
                                                • {{ Str::limit(basename($material->file_path), 30) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                @if($material->type == 'file' && Storage::disk('public')->exists($material->file_path))
                                    <a href="{{ asset('storage/' . $material->file_path) }}" 
                                       target="_blank" 
                                       class="text-sm px-3 py-1 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="bi bi-download mr-1"></i> Download
                                    </a>
                                @elseif($material->type == 'url' && $material->url)
                                    <a href="{{ $material->url }}" 
                                       target="_blank" 
                                       class="text-sm px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <i class="bi bi-box-arrow-up-right mr-1"></i> Visit Link
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Quizzes Section -->
            @if($course->quizzes->count() > 0)
            <div class="detail-row">
                <div class="detail-label">
                    <i class="bi bi-question-circle"></i>
                    Quizzes
                </div>
                <div class="detail-value">
                    <div class="space-y-4">
                        @foreach($course->quizzes as $quiz)
                        <div class="quiz-card">
                            <div class="quiz-header">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $quiz->title }}</h4>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-xs text-gray-500">
                                            <i class="bi bi-clock mr-1"></i> {{ $quiz->duration }} mins
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="bi bi-list-check mr-1"></i> {{ $quiz->questions->count() }} questions
                                        </span>
                                        @if($quiz->instructions)
                                        <span class="text-xs text-blue-500 cursor-help" title="{{ $quiz->instructions }}">
                                            <i class="bi bi-info-circle mr-1"></i> Instructions
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="quiz-status">
                                    <i class="bi bi-check-circle text-green-500 mr-1"></i> Active
                                </span>
                            </div>
                            
                            @if($quiz->questions->count() > 0)
                            <div class="quiz-questions mt-3">
                                <p class="text-xs font-medium text-gray-500 mb-2">Sample Questions:</p>
                                <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                    @foreach($quiz->questions->take(3) as $index => $question)
                                    <div class="question-preview">
                                        <p class="text-sm font-medium">
                                            {{ $index + 1 }}. {{ Str::limit($question->question, 60) }}
                                        </p>
                                        <div class="grid grid-cols-2 gap-2 mt-1 ml-3">
                                            @for($i = 1; $i <= 4; $i++)
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full {{ $question->correct_answer == $i ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                                <p class="text-xs text-gray-600">
                                                    {{ Str::limit($question->{'option'.$i}, 25) }}
                                                </p>
                                            </div>
                                            @endfor
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    @if($quiz->questions->count() > 3)
                                    <p class="text-xs text-gray-400 text-center">
                                        + {{ $quiz->questions->count() - 3 }} more questions
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Meta Information -->
            <div class="meta-info">
                <div class="meta-item">
                    <i class="bi bi-calendar-plus"></i>
                    <strong>Created:</strong> 
                    {{ $course->created_at->format('M d, Y h:i A') }}
                </div>
                <div class="meta-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong>Updated:</strong> 
                    {{ $course->updated_at->format('M d, Y h:i A') }}
                </div>
                @if($course->materials->count() > 0 || $course->quizzes->count() > 0)
                <div class="meta-item">
                    <i class="bi bi-pie-chart"></i>
                    <strong>Content:</strong> 
                    {{ $course->materials->count() }} materials, 
                    {{ $course->quizzes->count() }} quizzes, 
                    {{ $course->quizzes->sum(function($quiz) { return $quiz->questions->count(); }) }} questions
                </div>
                @endif
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.courses.index') }}" class="btn-action btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Back to List
                </a>
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Additional styles for view page */
.material-item {
    @apply p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors;
}

.quiz-card {
    @apply p-4 bg-white rounded-xl border border-gray-200 shadow-sm;
}

.quiz-header {
    @apply flex justify-between items-start;
}

.quiz-status {
    @apply px-2 py-1 text-xs bg-green-50 text-green-700 rounded-full font-medium;
}

.question-preview {
    @apply p-3 bg-gray-50 rounded-lg border border-gray-100;
}

.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.description-box {
    @apply whitespace-pre-line bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-700;
}

.price-display {
    @apply text-xl font-bold text-gray-800 flex items-center;
}

.price-display .currency {
    @apply text-lg mr-1;
}
</style>
@endpush

