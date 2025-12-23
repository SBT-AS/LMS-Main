@extends('frontend.layouts.app')

@section('title', 'Taking Quiz - ' . $quiz->title)

@push('styles')
<style>
    .quiz-question-card {
        display: none;
    }
    .quiz-question-card.active {
        display: block;
    }
    .option-btn {
        display: block;
        width: 100%;
        padding: 1rem 1.5rem;
        margin-bottom: 0.75rem;
        text-align: left;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #fff;
        transition: all 0.2s;
    }
    .option-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--accent-color);
    }
    .option-input:checked + .option-btn {
        background: rgba(16, 185, 129, 0.2);
        border-color: #10b981;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card-glass p-4 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0 text-white">{{ $quiz->title }}</h4>
                    <p class="text-muted small mb-0">{{ $course->title }}</p>
                </div>
                <div class="text-end">
                    <div id="quiz-timer" class="h4 fw-bold text-accent mb-0">--:--</div>
                    <small class="text-muted">Time Remaining</small>
                </div>
            </div>

            <form id="quiz-form" action="{{ route('student.quizzes.submit', [$course->id, $quiz->id, $attempt->id]) }}" method="POST">
                @csrf
                
                @foreach($questions as $index => $question)
                    <div class="quiz-question-card card-glass p-4 mb-4 {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="badge bg-primary rounded-pill">Question {{ $index + 1 }} of {{ $questions->count() }}</span>
                        </div>
                        
                        <h5 class="fw-bold mb-4 text-white">{{ $question->question }}</h5>

                        <div class="options-container">
                            @for($i = 1; $i <= 4; $i++)
                                @php $option = "option" . $i; @endphp
                                @if($question->$option)
                                    <label class="w-100 cursor-pointer">
                                        <input type="radio" name="question_{{ $question->id }}" value="{{ $i }}" class="option-input d-none" required>
                                        <div class="option-btn">
                                            <span class="me-3 opacity-50">{{ chr(64 + $i) }}.</span>
                                            {{ $question->$option }}
                                        </div>
                                    </label>
                                @endif
                            @endfor
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-4 border-top border-light border-opacity-10">
                            @if($index > 0)
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 prev-question">Previous</button>
                            @else
                                <div></div>
                            @endif

                            @if($index < $questions->count() - 1)
                                <button type="button" class="btn btn-primary rounded-pill px-4 next-question">Next Question</button>
                            @else
                                <button type="button" class="btn btn-success rounded-pill px-5 submit-quiz">Submit Quiz</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </form>
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-glass border-0">
            <div class="modal-body p-4 text-center">
                <i class="bi bi-question-circle display-1 text-primary mb-4 d-block"></i>
                <h4 class="fw-bold text-white">Submit your quiz?</h4>
                <p class="text-muted mb-4">You still have time remaining. Please double-check your answers if needed.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Go Back</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" id="confirm-submit">Yes, Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentQuestion = 0;
        const totalQuestions = {{ $questions->count() }};
        const cards = document.querySelectorAll('.quiz-question-card');
        const nextBtns = document.querySelectorAll('.next-question');
        const prevBtns = document.querySelectorAll('.prev-question');
        const submitBtn = document.querySelector('.submit-quiz');
        const confirmSubmitBtn = document.getElementById('confirm-submit');
        const submitModal = new bootstrap.Modal(document.getElementById('submitModal'));

        // Navigation
        nextBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const currentInputs = cards[currentQuestion].querySelectorAll('input[type="radio"]:checked');
                if (currentInputs.length === 0) {
                    alert('Please select an answer before proceeding.');
                    return;
                }
                cards[currentQuestion].classList.remove('active');
                currentQuestion++;
                cards[currentQuestion].classList.add('active');
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                cards[currentQuestion].classList.remove('active');
                currentQuestion--;
                cards[currentQuestion].classList.add('active');
            });
        });

        submitBtn.addEventListener('click', () => {
            const currentInputs = cards[currentQuestion].querySelectorAll('input[type="radio"]:checked');
            if (currentInputs.length === 0) {
                alert('Please select an answer for the last question.');
                return;
            }
            submitModal.show();
        });

        confirmSubmitBtn.addEventListener('click', () => {
            document.getElementById('quiz-form').submit();
        });

        // Timer
        let duration = {{ $quiz->duration * 60 }};
        const timerDisplay = document.getElementById('quiz-timer');

        function startTimer() {
            const timer = setInterval(function() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;

                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerDisplay.textContent = minutes + ':' + seconds;

                if (--duration < 0) {
                    clearInterval(timer);
                    alert('Time is up! Your quiz will be submitted automatically.');
                    document.getElementById('quiz-form').submit();
                }
                
                if (duration < 60) {
                    timerDisplay.classList.add('text-danger');
                }
            }, 1000);
        }

        startTimer();
    });
</script>
@endpush
