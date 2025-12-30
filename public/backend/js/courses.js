/**
 * Course Editor Module
 * Handles dynamic addition/removal of materials, quizzes, and questions.
 */

const CourseEditor = {
    materialCount: 0,
    quizCount: 0,
    questionCounts: {},

    /**
     * Initialize the editor with existing counts (for edit mode)
     */
    init: function (config) {
        this.materialCount = config.materialCount || 0;
        this.quizCount = config.quizCount || 0;
        if (config.questionCounts) {
            this.questionCounts = config.questionCounts;
        }

        // Initialize sections if they have content
        if (this.materialCount > 0) {
            const select = document.getElementById('includeMaterial');
            if (select) {
                select.value = '1';
                this.toggleMaterialSection();
            }
        }
        if (this.quizCount > 0) {
            const select = document.getElementById('includeQuiz');
            if (select) {
                select.value = '1';
                this.toggleQuizSection();
            }
        }
    },

    /**
     * Get next index for safe additions
     */
    getNextIndex: function () {
        const counts = Object.values(this.questionCounts);
        const maxQuestion = counts.length > 0 ? Math.max(...counts) : 0;
        return Math.max(this.materialCount, this.quizCount, maxQuestion) + Date.now();
        // Added Date.now() to ensure uniqueness if items are deleted and added rapidly
    },

    /* =========================================
       MATERIALS MANAGEMENT
       ========================================= */

    toggleMaterialSection: function () {
        const select = document.getElementById('includeMaterial');
        const section = document.getElementById('materialSection');
        if (!select || !section) return;

        if (select.value === '1') {
            section.classList.remove('hidden');
            section.classList.add('active');
            if (this.materialCount === 0 && document.querySelectorAll('#materialContainer .feature-item').length === 0) {
                this.addMaterial();
            }
        } else {
            section.classList.remove('active');
            section.classList.add('hidden');
        }
    },

    addMaterial: function () {
        const container = document.getElementById('materialContainer');
        if (!container) return;

        // Use a timestamp-based index to avoid collisions
        const nextIndex = 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000);

        const div = document.createElement('div');
        div.className = 'feature-item bg-gray-50 p-5 rounded-xl mb-4 border border-gray-200 shadow-sm relative group transition-all hover:border-indigo-200';
        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 pr-8">
                <!-- Title -->
                <div class="col-span-1 md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Material Title</label>
                    <input type="text" name="materials[${nextIndex}][title]" class="input-field bg-white" placeholder="e.g., Intro PDF to Chapter 1" >
                </div>

                 <!-- Source Type Selector -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Source</label>
                    <select name="materials[${nextIndex}][type]" class="input-field bg-white appearance-none" onchange="CourseEditor.toggleMaterialInput(this)">
                        <option value="file">File Upload</option>
                        <option value="url">External Link</option>
                    </select>
                </div>

                 <!-- Material Type Selector -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Content Type</label>
                    <select name="materials[${nextIndex}][material_type]" class="input-field bg-white appearance-none material-type-select">
                        <option value="video">Video</option>
                        <option value="pdf">PDF</option>
                        <option value="image">Image</option>
                    </select>
                </div>
                
                <!-- Content Input (File or URL) -->
                <div class="col-span-1 md:col-span-5 material-content-box">
                    <div class="file-input-wrapper">
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Upload File</label>
                        <input type="file" name="materials[${nextIndex}][file]" class="input-field p-1 bg-white focus:outline-none" >
                    </div>
                    <div class="url-input-wrapper hidden">
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">External URL</label>
                        <input type="url" name="materials[${nextIndex}][url]" class="input-field bg-white" placeholder="https://example.com/resource">
                    </div>
                </div>
            </div>
            
            <button type="button" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all" onclick="CourseEditor.removeMaterial(this)" title="Remove Material">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        this.materialCount++;

        // Initialize Select2 for new elements
        if (typeof AjaxCrud !== 'undefined') {
            AjaxCrud.select2(div);
        } else if ($.fn.select2) {
            $(div).find('select:not(.no-select2)').select2({
                width: '100%',
                placeholder: 'Select option',
                allowClear: true
            });
        }
    },

    toggleMaterialInput: function (select) {
        const parent = select.closest('.feature-item');
        const typeSelect = parent.querySelector('.material-type-select');
        const fileWrapper = parent.querySelector('.file-input-wrapper');
        const urlWrapper = parent.querySelector('.url-input-wrapper');
        const fileInput = fileWrapper.querySelector('input');
        const urlInput = urlWrapper.querySelector('input');

        if (select.value === 'url') {
            // URL mode
            fileWrapper.classList.add('hidden');
            if (fileInput) fileInput.required = false;

            urlWrapper.classList.remove('hidden');
            if (urlInput) urlInput.required = true;

            // Handle Type Select: Add URL option, select it, and disable
            if (!typeSelect.querySelector('option[value="url"]')) {
                const opt = document.createElement('option');
                opt.value = 'url';
                opt.text = 'External URL';
                typeSelect.add(opt);
            }
            typeSelect.value = 'url';
            typeSelect.disabled = true;

            // Ensure value is sent by using a hidden redundant input if needed, 
            // but Laravel will handle it if we either enable on submit or use a hidden field.
            // Let's add a hidden field to ensure the value is posted.
            let hidden = parent.querySelector('.hidden-type-input');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.className = 'hidden-type-input';
                hidden.name = typeSelect.name;
                parent.appendChild(hidden);
            }
            hidden.value = 'url';
        } else {
            // File mode
            urlWrapper.classList.add('hidden');
            if (urlInput) urlInput.required = false;

            fileWrapper.classList.remove('hidden');
            if (fileInput) fileInput.required = true;

            // Handle Type Select: Enable, remove URL option
            typeSelect.disabled = false;
            const urlOpt = typeSelect.querySelector('option[value="url"]');
            if (urlOpt) urlOpt.remove();

            if (typeSelect.value === 'url' || !typeSelect.value) {
                typeSelect.value = 'video';
            }

            // Remove hidden input
            const hidden = parent.querySelector('.hidden-type-input');
            if (hidden) hidden.remove();
        }
    },

    removeMaterial: function (button) {
        button.closest('.feature-item').remove();
        // We don't decrement this.materialCount purely to retain unique index progression if simplistic indexing was used, 
        // but with Date.now() it's fine.
    },

    /* =========================================
       QUIZZES MANAGEMENT
       ========================================= */

    toggleQuizSection: function () {
        const select = document.getElementById('includeQuiz');
        const section = document.getElementById('quizSection');
        if (!select || !section) return;

        if (select.value === '1') {
            section.classList.remove('hidden');
            section.classList.add('active');
            if (this.quizCount === 0 && document.querySelectorAll('#quizContainer .feature-item').length === 0) {
                this.addQuiz();
            }
        } else {
            section.classList.remove('active');
            section.classList.add('hidden');
        }
    },

    addQuiz: function () {
        const container = document.getElementById('quizContainer');
        if (!container) return;

        // Use a timestamp-based index
        const nextIndex = 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000);

        const div = document.createElement('div');
        div.className = 'feature-item bg-white p-6 rounded-xl mb-6 border border-gray-200 shadow-sm relative group';
        div.dataset.quizIndex = nextIndex;

        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 border-b border-gray-100 pb-4 mb-4">
                <div class="col-span-1 md:col-span-8">
                     <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Quiz Title</label>
                    <input type="text" name="quizzes[${nextIndex}][title]" class="input-field bg-gray-50" placeholder="e.g., Module 1 Final Assessment" >
                </div>
                <div class="col-span-1 md:col-span-4">
                     <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Duration (Mins)</label>
                    <input type="number" name="quizzes[${nextIndex}][duration]" class="input-field bg-gray-50" placeholder="e.g., 45" min="1" >
                </div>
                <div class="col-span-1 md:col-span-12">
                     <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Instructions for Students</label>
                    <textarea name="quizzes[${nextIndex}][instructions]" rows="2" class="input-field bg-gray-50" placeholder="Enter specific instructions for this quiz..."></textarea>
                </div>
            </div>

            <!-- Questions Container -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide"><i class="bi bi-list-check mr-2"></i>Questions</h4>
                    <button type="button" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors" onclick="CourseEditor.addQuestion('${nextIndex}')">
                        + Add Question
                    </button>
                </div>
                <div id="quiz-questions-${nextIndex}" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <!-- Questions will go here -->
                </div>
            </div>

            <button type="button" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all" onclick="CourseEditor.removeQuiz(this)" title="Remove Quiz">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);

        // Add one initial question
        this.addQuestion(nextIndex);

        this.quizCount++;
        this.questionCounts[nextIndex] = 1;

        // Initialize Select2 for new elements
        if (typeof AjaxCrud !== 'undefined') {
            AjaxCrud.select2(div);
        } else if ($.fn.select2) {
            $(div).find('select:not(.no-select2)').select2({
                width: '100%',
                placeholder: 'Select option',
                allowClear: true
            });
        }
    },

    removeQuiz: function (button) {
        const quizDiv = button.closest('.feature-item');
        const quizIndex = quizDiv.dataset.quizIndex;

        // Clean up tracking
        delete this.questionCounts[quizIndex];

        quizDiv.remove();

    },

    /* =========================================
       QUESTIONS MANAGEMENT
       ========================================= */

    addQuestion: function (quizIndex) {
        const container = document.getElementById(`quiz-questions-${quizIndex}`);
        if (!container) return;

        // Use a timestamp-based index for questions too
        const questionIndex = 'q_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
        const questionNumber = container.children.length + 1;

        const div = document.createElement('div');
        div.className = 'question-card bg-white p-4 rounded-lg border border-gray-200 relative animate-fade-in hover:border-indigo-300 transition-colors';

        div.innerHTML = `
            <div class="mb-3 pr-8">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Question <span class="q-num">${questionNumber}</span></label>
                <input type="text" name="quizzes[${quizIndex}][questions][${questionIndex}][text]" class="input-field font-medium placeholder-gray-400" placeholder="Type your question here..." >
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                ${[1, 2, 3, 4].map(opt => `
                <div class="relative">
                    <div class="flex items-center absolute left-3 top-1/2 -translate-y-1/2">
                        <input type="radio" name="quizzes[${quizIndex}][questions][${questionIndex}][correct]" value="${opt}" class="text-indigo-600 focus:ring-indigo-500 cursor-pointer" title="Mark as correct answer" ${opt === 1 ? 'checked' : ''}>
                    </div>
                    <input type="text" name="quizzes[${quizIndex}][questions][${questionIndex}][options][${opt}]" class="input-field pl-8 text-sm" placeholder="Option ${opt}">
                </div>
                `).join('')}
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Explanation (Visible after answer)</label>
                <textarea name="quizzes[${quizIndex}][questions][${questionIndex}][explanation]" rows="2" class="input-field text-sm" placeholder="Explain why the correct answer is correct..."></textarea>
            </div>

            <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 p-1 rounded transition-colors" onclick="CourseEditor.removeQuestion(this, '${quizIndex}')" title="Delete Question">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        container.appendChild(div);

        // Update tracking
        this.questionCounts[quizIndex] = (this.questionCounts[quizIndex] || 0) + 1;
    },

    removeQuestion: function (button, quizIndex) {
        const container = button.closest('.space-y-4');
        button.closest('.question-card').remove();

        this.reorderQuestions(container);

        // Update tracking
        if (this.questionCounts[quizIndex]) {
            this.questionCounts[quizIndex]--;
        }
    },

    reorderQuestions: function (container) {
        const questions = container.children;
        Array.from(questions).forEach((q, idx) => {
            const labelSpan = q.querySelector('.q-num');
            if (labelSpan) labelSpan.textContent = idx + 1;
        });
    },



    initImageUpload: function () {
        const input = document.getElementById('imageInput');
        const uploadZone = document.getElementById('uploadZone');

        if (input && uploadZone) {
            uploadZone.addEventListener('click', () => input.click());

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('imagePreview');
                        if (preview && uploadZone) {
                            preview.querySelector('img').src = e.target.result;
                            preview.classList.remove('hidden');
                            uploadZone.classList.add('hidden');
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    },

    removeImage: function () {
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');
        const uploadZone = document.getElementById('uploadZone');

        if (input) input.value = '';
        if (preview) {
            preview.classList.add('hidden');
            preview.querySelector('img').src = '';
        }
        if (uploadZone) uploadZone.classList.remove('hidden');
    }
};

// Initialize Image Upload listeners globally on load if they exist
document.addEventListener('DOMContentLoaded', function () {
    CourseEditor.initImageUpload();
});

// Global Helper for image removal calling the object method
function removeImage() {
    CourseEditor.removeImage();
}
