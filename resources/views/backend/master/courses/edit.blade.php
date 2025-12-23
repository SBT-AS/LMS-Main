@extends('backend.layouts.master')

@section('title', 'Edit Course')
@section('header_title', 'Course Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Course</h1>
            <p class="text-gray-500 text-sm mt-1">Update course details, materials & quizzes</p>
        </div>
        <a href="{{ route('admin.courses.index') }}"
           class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <form id="crudForm" action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Section 1: Course Details -->
        <div class="form-section">
            <div class="section-header">
                <i class="bi bi-book"></i>
                <div>
                    <h3 class="font-semibold text-lg">Course Details</h3>
                    <p class="text-sm opacity-90">Basic information about your course</p>
                </div>
            </div>
            <div class="section-body">
                <!-- Title -->
                <div class="input-group">
                    <label class="input-label">
                        Course Title <span class="required">*</span>
                    </label>
                    <input type="text" name="title" class="input-field" 
                           placeholder="Enter an engaging title for your course"
                           value="{{ old('title', $course->title) }}" required>
                </div>

                <!-- Description -->
                <div class="input-group">
                    <label class="input-label">
                        Description
                    </label>
                    <textarea name="description" rows="4" class="input-field" 
                              placeholder="Describe what students will learn in this course...">{{ old('description', $course->description) }}</textarea>
                </div>

                <!-- Price & Image Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Price -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Price (INR) <span class="required">*</span>
                        </label>
                        <div class="price-input-wrapper">
                            <span class="currency-symbol">₹</span>
                            <input type="number" name="price" class="input-field" 
                                   placeholder="0.00" step="0.01" min="0"
                                   value="{{ old('price', $course->price) }}" required>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Set to 0 for free courses</p>
                    </div>

                    <!-- Image Upload -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Course Thumbnail
                        </label>
                        @if($course->image)
                            <div class="mb-3">
                                <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                                <img src="{{ asset('storage/courses/' . $course->image) }}" 
                                     alt="{{ $course->title }}" 
                                     class="h-32 w-auto rounded-lg object-cover">
                            </div>
                        @endif
                        <div id="uploadZone" class="image-upload-zone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p class="font-medium">Click to upload new image</p>
                            <span class="hint">PNG, JPG up to 2MB</span>
                            <input type="file" name="image" accept="image/*" class="hidden" id="imageInput">
                        </div>
                        <div id="imagePreview" class="image-preview mt-4 hidden">
                            <img src="" alt="Preview">
                            <button type="button" class="remove-btn" onclick="removeImage()">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Video Upload -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Course Preview Video (Optional)
                        </label>
                        @if($course->video)
                            <div class="mb-3">
                                <p class="text-sm text-gray-500 mb-2">Current Video:</p>
                                <video src="{{ asset('storage/courses/' . $course->video) }}" controls class="w-full rounded-lg" style="max-height: 150px;"></video>
                            </div>
                        @endif
                        <div id="videoUploadZone" class="image-upload-zone" onclick="document.getElementById('videoInput').click()">
                            <i class="bi bi-film"></i>
                            <p class="font-medium">Click to upload video</p>
                            <span class="hint">MP4, WebM up to 50MB</span>
                            <input type="file" name="video" accept="video/mp4,video/webm" class="hidden" id="videoInput" onchange="previewVideo(this)">
                        </div>
                        <div id="videoPreview" class="image-preview mt-4 hidden">
                            <video controls class="w-full rounded-lg" style="max-height: 150px;"></video>
                            <button type="button" class="remove-btn" onclick="removeVideo()">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dropdowns Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <!-- Category Dropdown -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Category <span class="required">*</span>
                        </label>
                        <select name="category_id" class="input-field appearance-none" style="background-image: none;" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $course->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Status <span class="required">*</span>
                        </label>
                        <select name="status" class="input-field appearance-none" style="background-image: none;">
                            <option value="1" {{ old('status', $course->status) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $course->status) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Live Class Dropdown -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Live Class Available
                        </label>
                        <select name="live_class" class="input-field appearance-none" style="background-image: none;">
                            <option value="0" {{ old('live_class', $course->live_class) == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('live_class', $course->live_class) == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </div>
                
                <!-- Extra Features Dropdowns -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Include Material Dropdown -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Include Study Material
                        </label>
                        <select name="include_material" id="includeMaterial" class="input-field appearance-none" onchange="CourseEditor.toggleMaterialSection()">
                            <option value="0" {{ $course->materials->isEmpty() ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $course->materials->isNotEmpty() ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <!-- Include Quiz Dropdown -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Include Quiz
                        </label>
                        <select name="include_quiz" id="includeQuiz" class="input-field appearance-none" onchange="CourseEditor.toggleQuizSection()">
                            <option value="0" {{ $course->quizzes->isEmpty() ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $course->quizzes->isNotEmpty() ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Study Material (Dynamic) -->
        <div id="materialSection" class="form-section {{ $course->materials->isNotEmpty() ? '' : 'hidden' }}">
            <div class="section-header">
                <i class="bi bi-file-earmark-text"></i>
                <div>
                    <h3 class="font-semibold text-lg">Study Materials</h3>
                    <p class="text-sm opacity-90">Update study materials for this course</p>
                </div>
            </div>
            <div class="section-body">
                <div id="materialContainer">
                    <!-- Existing materials will be populated by JavaScript -->
                    @foreach($course->materials as $index => $material)
                    <div class="feature-item bg-gray-50 p-5 rounded-xl mb-4 border border-gray-200 shadow-sm relative group transition-all hover:border-indigo-200 existing-material" data-id="{{ $material->id }}">
                        <input type="hidden" name="materials[{{ $index }}][id]" value="{{ $material->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 pr-8">
                            <!-- Title -->
                            <div class="col-span-1 md:col-span-3">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Material Title</label>
                                <input type="text" name="materials[{{ $index }}][title]" class="input-field bg-white" 
                                       value="{{ $material->title }}" placeholder="e.g., Intro PDF to Chapter 1" required>
                            </div>

                            <!-- Source Selector -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Source</label>
                                <select name="materials[{{ $index }}][type]" class="input-field bg-white appearance-none" onchange="CourseEditor.toggleMaterialInput(this)">
                                    <option value="file" {{ $material->type == 'file' ? 'selected' : '' }}>File Upload</option>
                                    <option value="url" {{ $material->type == 'url' ? 'selected' : '' }}>External Link</option>
                                </select>
                            </div>

                            <!-- Content Type Selector -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Content Type</label>
                                <select name="materials[{{ $index }}][material_type]" class="input-field bg-white appearance-none material-type-select" {{ $material->type == 'url' ? 'disabled' : '' }}>
                                    <option value="video" {{ $material->material_type == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="pdf" {{ $material->material_type == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="image" {{ $material->material_type == 'image' ? 'selected' : '' }}>Image</option>
                                    @if($material->type == 'url')
                                        <option value="url" selected>External URL</option>
                                    @endif
                                </select>
                                @if($material->type == 'url')
                                    <input type="hidden" name="materials[{{ $index }}][material_type]" value="url" class="hidden-type-input">
                                @endif
                            </div>
                            
                            <!-- Content Input (File or URL) -->
                            <div class="col-span-1 md:col-span-5 material-content-box">
                                @if($material->type == 'file')
                                <div class="file-input-wrapper">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1 line-clamp-1" title="{{ basename($material->file_path) }}">Current: {{ basename($material->file_path) }}</label>
                                    <input type="file" name="materials[{{ $index }}][file]" class="input-field p-1 bg-white focus:outline-none">
                                    <input type="hidden" name="materials[{{ $index }}][existing_file]" value="{{ $material->file_path }}">
                                </div>
                                <div class="url-input-wrapper hidden">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">External URL</label>
                                    <input type="url" name="materials[{{ $index }}][url]" class="input-field bg-white" placeholder="https://example.com/resource">
                                </div>
                                @else
                                <div class="file-input-wrapper hidden">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Upload File</label>
                                    <input type="file" name="materials[{{ $index }}][file]" class="input-field p-1 bg-white focus:outline-none">
                                </div>
                                <div class="url-input-wrapper">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">External URL</label>
                                    <input type="url" name="materials[{{ $index }}][url]" class="input-field bg-white" 
                                           value="{{ $material->url }}" placeholder="https://example.com/resource" required>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <button type="button" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all" onclick="CourseEditor.removeMaterial(this)" title="Remove Material">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="add-btn mt-4" onclick="CourseEditor.addMaterial()">
                    <i class="bi bi-plus-circle"></i>
                    Add New Material
                </button>
            </div>
        </div>

        <!-- Section: Quizzes (Dynamic) -->
        <div id="quizSection" class="form-section {{ $course->quizzes->isNotEmpty() ? '' : 'hidden' }}">
            <div class="section-header">
                <i class="bi bi-question-circle"></i>
                <div>
                    <h3 class="font-semibold text-lg">Quizzes</h3>
                    <p class="text-sm opacity-90">Update quizzes for this course</p>
                </div>
            </div>
            <div class="section-body">
                <div id="quizContainer">
                    @foreach($course->quizzes as $qIndex => $quiz)
                    <div class="feature-item bg-white p-6 rounded-xl mb-6 border border-gray-200 shadow-sm relative group existing-quiz" data-id="{{ $quiz->id }}" data-quiz-index="{{ $qIndex }}">
                        <input type="hidden" name="quizzes[{{ $qIndex }}][id]" value="{{ $quiz->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 border-b border-gray-100 pb-4 mb-4">
                            <div class="col-span-1 md:col-span-8">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Quiz Title</label>
                                <input type="text" name="quizzes[{{ $qIndex }}][title]" class="input-field bg-gray-50" 
                                       value="{{ $quiz->title }}" placeholder="e.g., Module 1 Final Assessment" required>
                            </div>
                            <div class="col-span-1 md:col-span-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Duration (Mins)</label>
                                <input type="number" name="quizzes[{{ $qIndex }}][duration]" class="input-field bg-gray-50" 
                                       value="{{ $quiz->duration }}" placeholder="e.g., 45" min="1" required>
                            </div>
                            <div class="col-span-1 md:col-span-12">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Instructions for Students</label>
                                <textarea name="quizzes[{{ $qIndex }}][instructions]" rows="2" class="input-field bg-gray-50" 
                                          placeholder="Enter specific instructions for this quiz...">{{ $quiz->instructions }}</textarea>
                            </div>
                        </div>

                        <!-- Questions Container -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                                    <i class="bi bi-list-check mr-2"></i>Questions
                                </h4>
                                <button type="button" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors" onclick="CourseEditor.addQuestion('{{ $qIndex }}')">
                                    + Add Question
                                </button>
                            </div>
                            <div id="quiz-questions-{{ $qIndex }}" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($quiz->questions as $qsIndex => $question)
                                <div class="question-card bg-white p-4 rounded-lg border border-gray-200 relative">
                                    <input type="hidden" name="quizzes[{{ $qIndex }}][questions][{{ $qsIndex }}][id]" value="{{ $question->id }}">
                                    
                                    <div class="mb-3 pr-8">
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Question {{ $qsIndex + 1 }}</label>
                                        <input type="text" name="quizzes[{{ $qIndex }}][questions][{{ $qsIndex }}][text]" 
                                               class="input-field font-medium placeholder-gray-400" 
                                               value="{{ $question->question }}" placeholder="Type your question here..." required>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                        @for($i = 1; $i <= 4; $i++)
                                        <div class="relative">
                                            <div class="flex items-center absolute left-3 top-1/2 -translate-y-1/2">
                                                <input type="radio" name="quizzes[{{ $qIndex }}][questions][{{ $qsIndex }}][correct]" 
                                                       value="{{ $i }}" class="text-indigo-600 focus:ring-indigo-500 cursor-pointer" 
                                                       title="Mark as correct answer" {{ $question->correct_answer == $i ? 'checked' : '' }}>
                                            </div>
                                            <input type="text" name="quizzes[{{ $qIndex }}][questions][{{ $qsIndex }}][options][{{ $i }}]" 
                                                   class="input-field pl-8 text-sm" 
                                                   value="{{ $question->{'option'.$i} }}" placeholder="Option {{ $i }}" required>
                                        </div>
                                        @endfor
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Explanation (Visible after answer)</label>
                                        <textarea name="quizzes[{{ $qIndex }}][questions][{{ $qsIndex }}][explanation]" rows="2" 
                                                  class="input-field text-sm" placeholder="Explain why the correct answer is correct...">{{ $question->explanation }}</textarea>
                                    </div>

                                    <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 p-1 rounded transition-colors" 
                                            onclick="CourseEditor.removeQuestion(this, '{{ $qIndex }}')" title="Delete Question">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all" 
                                onclick="CourseEditor.removeQuiz(this)" title="Remove Quiz">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="add-btn mt-4" onclick="CourseEditor.addQuiz()">
                    <i class="bi bi-plus-circle"></i>
                    Add New Quiz
                </button>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="flex justify-end gap-4 mb-8">
            <a href="{{ route('admin.courses.index') }}" 
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                Cancel
            </a>
            <button type="submit" id="saveBtn" class="submit-btn flex items-center gap-2">
                <i class="bi bi-check-lg"></i>
                Update Course
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewVideo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                const video = document.querySelector('#videoPreview video');
                video.src = e.target.result;
                document.getElementById('videoUploadZone').classList.add('hidden');
                document.getElementById('videoPreview').classList.remove('hidden');
                video.load();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeVideo() {
        const input = document.getElementById('videoInput');
        input.value = '';
        document.getElementById('videoUploadZone').classList.remove('hidden');
        document.getElementById('videoPreview').classList.add('hidden');
        document.querySelector('#videoPreview video').src = '';
    }

    // Add Image preview logic if not already present
    const imageInput = document.getElementById('imageInput');
    const uploadZone = document.getElementById('uploadZone');

    if(uploadZone && imageInput) {
        uploadZone.addEventListener('click', () => imageInput.click());
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('#imagePreview img');
                    preview.src = e.target.result;
                    uploadZone.classList.add('hidden');
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    window.removeImage = function() {
        const input = document.getElementById('imageInput');
        input.value = '';
        document.getElementById('uploadZone').classList.remove('hidden');
        document.getElementById('imagePreview').classList.add('hidden');
        document.querySelector('#imagePreview img').src = '';
        // If editing, you might want to show the current image again or just leave blank to not update
    }

    document.addEventListener('DOMContentLoaded', function() {
        CourseEditor.init({
            materialCount: {{ $course->materials->count() }},
            quizCount: {{ $course->quizzes->count() }},
            questionCounts: {
                @foreach($course->quizzes as $qIndex => $quiz)
                '{{ $qIndex }}': {{ $quiz->questions->count() }},
                @endforeach
            }
        });
    });
</script>
@endpush
