/**
 * Quiz Editor Module
 * Handles dynamic addition/removal of questions in the quiz form.
 */
const QuizEditor = {
    questionCount: 0,
    containerId: 'questionsContainer',

    init: function (initialCount) {
        this.questionCount = initialCount || 0;
        if (this.questionCount === 0) {
            this.addQuestion();
        }
    },

    addQuestion: function () {
        const container = document.getElementById(this.containerId);
        if (!container) return;

        const questionDiv = document.createElement('div');
        questionDiv.className = 'question-item bg-white p-6 rounded-xl border border-gray-200 mb-4';
        questionDiv.id = `question-${this.questionCount}`;

        questionDiv.innerHTML = `
            <div class="flex justify-between items-start mb-4">
                <h4 class="font-semibold text-gray-700">Question ${this.questionCount + 1}</h4>
                <button type="button" onclick="QuizEditor.removeQuestion(${this.questionCount})" 
                        class="text-red-500 hover:text-red-700">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            
            <!-- Question Text -->
            <div class="input-group">
                <label class="input-label">Question <span class="required">*</span></label>
                <textarea name="questions[${this.questionCount}][question]" rows="2" class="input-field" 
                          placeholder="Enter your question..." required></textarea>
            </div>
            
            <!-- Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="input-group mb-0">
                    <label class="input-label">Option 1 <span class="required">*</span></label>
                    <input type="text" name="questions[${this.questionCount}][option1]" class="input-field" 
                           placeholder="Option 1" required>
                </div>
                <div class="input-group mb-0">
                    <label class="input-label">Option 2 <span class="required">*</span></label>
                    <input type="text" name="questions[${this.questionCount}][option2]" class="input-field" 
                           placeholder="Option 2" required>
                </div>
                <div class="input-group mb-0">
                    <label class="input-label">Option 3 <span class="required">*</span></label>
                    <input type="text" name="questions[${this.questionCount}][option3]" class="input-field" 
                           placeholder="Option 3" required>
                </div>
                <div class="input-group mb-0">
                    <label class="input-label">Option 4 <span class="required">*</span></label>
                    <input type="text" name="questions[${this.questionCount}][option4]" class="input-field" 
                           placeholder="Option 4" required>
                </div>
            </div>
            
            <!-- Correct Answer -->
            <div class="input-group">
                <label class="input-label">Correct Answer <span class="required">*</span></label>
                <select name="questions[${this.questionCount}][correct_answer]" class="input-field" required>
                    <option value="">Select correct answer</option>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            </div>
            
            <!-- Explanation -->
            <div class="input-group mb-0">
                <label class="input-label">Explanation (Optional)</label>
                <textarea name="questions[${this.questionCount}][explanation]" rows="2" class="input-field" 
                          placeholder="Explain why this answer is correct..."></textarea>
            </div>
        `;

        container.appendChild(questionDiv);
        this.questionCount++;
    },

    removeQuestion: function (index) {
        const questionDiv = document.getElementById(`question-${index}`);
        if (questionDiv) {
            questionDiv.remove();
        }
    }
};

// Global helpers if needed by legacy calls
window.addQuestion = () => QuizEditor.addQuestion();
window.removeQuestion = (index) => QuizEditor.removeQuestion(index);
