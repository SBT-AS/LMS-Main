@extends('backend.layouts.master')

@section('title', 'Edit Quiz')
@section('header_title', 'Quiz Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Quiz: {{ $quiz->title }}</h1>
            <p class="text-gray-500 text-sm mt-1">Update quiz details and questions</p>
        </div>
        <a href="{{ route('admin.courses.quizzes.index', $course) }}"
           class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Quizzes
        </a>
    </div>

    <form id="crudForm" action="{{ route('admin.courses.quizzes.update', [$course, $quiz]) }}" method="POST">
        @csrf
        @method('PUT')

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
                           value="{{ old('title', $quiz->title) }}" required>
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
                               value="{{ old('duration', $quiz->duration) }}" required>
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
                              placeholder="Enter instructions for students...">{{ old('instructions', $quiz->instructions) }}</textarea>
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
                    <p class="text-sm opacity-90">Edit multiple choice questions</p>
                </div>
            </div>
            <div class="section-body">
                <div id="questionsContainer">
                    @foreach($quiz->questions as $index => $question)
                        <div class="question-item bg-white p-6 rounded-xl border border-gray-200 mb-4" id="question-{{ $index }}">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-semibold text-gray-700">Question {{ $index + 1 }}</h4>
                                <button type="button" onclick="removeQuestion({{ $index }})" 
                                        class="text-red-500 hover:text-red-700">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                            
                            <!-- Question Text -->
                            <div class="input-group">
                                <label class="input-label">Question <span class="required">*</span></label>
                                <textarea name="questions[{{ $index }}][question]" rows="2" class="input-field" 
                                          placeholder="Enter your question..." required>{{ old("questions.$index.question", $question->question) }}</textarea>
                            </div>
                            
                            <!-- Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="input-group mb-0">
                                    <label class="input-label">Option 1 <span class="required">*</span></label>
                                    <input type="text" name="questions[{{ $index }}][option1]" class="input-field" 
                                           placeholder="Option 1" value="{{ old("questions.$index.option1", $question->option1) }}" required>
                                </div>
                                <div class="input-group mb-0">
                                    <label class="input-label">Option 2 <span class="required">*</span></label>
                                    <input type="text" name="questions[{{ $index }}][option2]" class="input-field" 
                                           placeholder="Option 2" value="{{ old("questions.$index.option2", $question->option2) }}" required>
                                </div>
                                <div class="input-group mb-0">
                                    <label class="input-label">Option 3 <span class="required">*</span></label>
                                    <input type="text" name="questions[{{ $index }}][option3]" class="input-field" 
                                           placeholder="Option 3" value="{{ old("questions.$index.option3", $question->option3) }}" required>
                                </div>
                                <div class="input-group mb-0">
                                    <label class="input-label">Option 4 <span class="required">*</span></label>
                                    <input type="text" name="questions[{{ $index }}][option4]" class="input-field" 
                                           placeholder="Option 4" value="{{ old("questions.$index.option4", $question->option4) }}" required>
                                </div>
                            </div>
                            
                            <!-- Correct Answer -->
                            <div class="input-group">
                                <label class="input-label">Correct Answer <span class="required">*</span></label>
                                <select name="questions[{{ $index }}][correct_answer]" class="input-field" required>
                                    <option value="">Select correct answer</option>
                                    <option value="1" {{ old("questions.$index.correct_answer", $question->correct_answer) == 1 ? 'selected' : '' }}>Option 1</option>
                                    <option value="2" {{ old("questions.$index.correct_answer", $question->correct_answer) == 2 ? 'selected' : '' }}>Option 2</option>
                                    <option value="3" {{ old("questions.$index.correct_answer", $question->correct_answer) == 3 ? 'selected' : '' }}>Option 3</option>
                                    <option value="4" {{ old("questions.$index.correct_answer", $question->correct_answer) == 4 ? 'selected' : '' }}>Option 4</option>
                                </select>
                            </div>
                            
                            <!-- Explanation -->
                            <div class="input-group mb-0">
                                <label class="input-label">Explanation (Optional)</label>
                                <textarea name="questions[{{ $index }}][explanation]" rows="2" class="input-field" 
                                          placeholder="Explain why this answer is correct...">{{ old("questions.$index.explanation", $question->explanation) }}</textarea>
                            </div>
                        </div>
                    @endforeach
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
            <button id="saveBtn" type="submit" class="submit-btn flex items-center gap-2">
                <i class="bi bi-check-lg"></i>
                Update Quiz
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
            QuizEditor.init({{ $quiz->questions->count() }});
        });
    </script>
@endpush

