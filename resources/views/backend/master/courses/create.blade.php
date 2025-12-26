@extends('backend.layouts.master')

@section('title', 'Create Course')
@section('header_title', 'Course Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Course</h1>
            <p class="text-gray-500 text-sm mt-1">Fill in the details to create a new course</p>
        </div>
        <a href="{{ route('admin.courses.index') }}"
           class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <form id="crudForm" action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                           value="{{ old('title') }}"   >
                </div>

                <!-- Description -->
                <div class="input-group">
                    <label class="input-label">
                        Description
                    </label>
                    <textarea name="description" rows="4" class="input-field" 
                              placeholder="Describe what students will learn in this course...">{{ old('description') }}</textarea>
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
                                   value="{{ old('price', 0) }}">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Set to 0 for free courses</p>
                    </div>

                    <!-- Image Upload -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Course Thumbnail
                        </label>
                        <div id="uploadZone" class="image-upload-zone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p class="font-medium">Click to upload image</p>
                            <span class="hint">PNG, JPG up to 2MB</span>
                        </div>
                        <input type="file" name="image" accept="image/*" class="hidden" id="imageInput">
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
                        <select name="category_id" class="input-field appearance-none" style="background-image: none;">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <!-- Custom arrow icon can be added via CSS or absolute positioning if needed, keeping it simple for now -->
                    </div>

                    <!-- Live Class Dropdown -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Live Class Available
                        </label>
                        <select name="live_class" id="liveClassSelect" class="input-field appearance-none" style="background-image: none;" onchange="toggleLiveLink()">
                            <option value="0" {{ old('live_class') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('live_class') == '1' ? 'selected' : '' }}>Yes</option>
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
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <!-- Include Quiz Dropdown -->
                    <div class="input-group mb-0">
                        <label class="input-label">
                            Include Quiz
                        </label>
                        <select name="include_quiz" id="includeQuiz" class="input-field appearance-none" onchange="CourseEditor.toggleQuizSection()">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Live Class Details (Dynamic) -->
        <div id="liveLinkSection" class="form-section {{ old('live_class') == '1' ? '' : 'hidden' }}">
            <div class="section-header">
                <i class="bi bi-camera-video"></i>
                <div>
                    <h3 class="font-semibold text-lg">Live Class Details</h3>
                    <p class="text-sm opacity-90">Manage your live lecture links</p>
                </div>
            </div>
            <div class="section-body">
                <div class="input-group mb-0">
                    <label class="input-label">
                        Live Class Link (Zoom/Google Meet/etc.) <span class="required">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="bi bi-link-45deg"></i>
                        </span>
                        <input type="url" name="live_class_link" class="input-field pl-10" 
                               placeholder="https://meet.google.com/abc-defg-hij"
                               value="{{ old('live_class_link') }}">
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Enter the full URL where students will join the session.</p>
                </div>
            </div>
        </div>

        <!-- Section: Study Material (Dynamic) -->
        <div id="materialSection" class="form-section hidden">
            <div class="section-header">
                <i class="bi bi-file-earmark-text"></i>
                <div>
                    <h3 class="font-semibold text-lg">Study Materials</h3>
                    <p class="text-sm opacity-90">Add study materials for this course</p>
                </div>
            </div>
            <div class="section-body">
                <div id="materialContainer">
                    <!-- Dynamic material fields will be added here -->
                </div>
                <button type="button" class="add-btn mt-4" onclick="CourseEditor.addMaterial()">
                    <i class="bi bi-plus-circle"></i>
                    Add Material
                </button>
            </div>
        </div>

        <!-- Section: Quizzes (Dynamic) -->
        <div id="quizSection" class="form-section hidden">
            <div class="section-header">
                <i class="bi bi-question-circle"></i>
                <div>
                    <h3 class="font-semibold text-lg">Quizzes</h3>
                    <p class="text-sm opacity-90">Add quizzes for this course</p>
                </div>
            </div>
            <div class="section-body">
                <div id="quizContainer">
                    <!-- Dynamic quiz fields will be added here -->
                </div>
                <button type="button" class="add-btn mt-4" onclick="CourseEditor.addQuiz()">
                    <i class="bi bi-plus-circle"></i>
                    Add Quiz
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
                Create Course
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

    // Image preview logic is handled by backend/js/courses.js CourseEditor.initImageUpload()

    // removeImage is handled by backend/js/courses.js global function

    function toggleLiveLink() {
        const select = document.getElementById('liveClassSelect');
        const linkSection = document.getElementById('liveLinkSection');
        if (select.value === '1') {
            linkSection.classList.remove('hidden');
        } else {
            linkSection.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        CourseEditor.init({
            materialCount: 0,
            quizCount: 0 
        });
    });
</script>
@endpush
