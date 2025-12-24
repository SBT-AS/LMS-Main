@extends('frontend.layouts.app')

@section('title', 'Quiz Result - ' . $quiz->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card-glass p-5 text-center mb-4">
                <div class="mb-4">
                    @if($attempt->score >= 70)
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-4" style="width: 100px; height: 100px;">
                            <i class="bi bi-trophy text-success display-4"></i>
                        </div>
                        <h2 class="fw-bold text-white">Congratulations!</h2>
                        <p class="text-muted">You passed the quiz with a great score.</p>
                    @else
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-4" style="width: 100px; height: 100px;">
                            <i class="bi bi-award text-warning display-4"></i>
                        </div>
                        <h2 class="fw-bold text-white">Keep Learning!</h2>
                        <p class="text-muted">You finished the quiz. Review your answers below to improve.</p>
                    @endif
                </div>

                <div class="row g-4 justify-content-center mb-5">
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light bg-opacity-5 rounded-4">
                            <h3 class="fw-bold text-accent mb-0">{{ $attempt->score }}%</h3>
                            <small class="text-muted">Final Score</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light bg-opacity-5 rounded-4">
                            <h3 class="fw-bold text-white mb-0">{{ $attempt->answers->where('is_correct', true)->count() }}</h3>
                            <small class="text-muted">Correct</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('student.quizzes.index', $course->id) }}" class="btn btn-outline-secondary rounded-pill px-4">Back to Quizzes</a>
                    <a href="{{ route('student.courses.classroom', $course->slug) }}" class="btn btn-primary rounded-pill px-4">Continue Classroom</a>
                </div>
            </div>

            <h4 class="fw-bold mb-4 text-white">Review Your Answers</h4>
            @foreach($attempt->answers as $index => $answer)
                <div class="card-glass p-4 mb-3 review-card {{ $index === 0 ? 'active' : '' }} {{ $answer->is_correct ? 'border-success' : 'border-danger' }}" 
                     style="border-width: 0 0 0 4px!important; display: {{ $index === 0 ? 'block' : 'none' }};" data-index="{{ $index }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="fw-bold mb-0 text-white">Question {{ $index + 1 }} of {{ $attempt->answers->count() }}</h6>
                        @if($answer->is_correct)
                            <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle me-1"></i> Correct</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle me-1"></i> Incorrect</span>
                        @endif
                    </div>
                    
                    <p class="mb-4">{{ $answer->question->question }}</p>

                    <div class="options-review">
                        @for($i = 1; $i <= 4; $i++)
                            @php 
                                $option = "option" . $i;
                                $isCorrectOption = ($i == $answer->question->correct_answer);
                                $isSelectedOption = ($i == $answer->selected_answer);
                            @endphp
                            @if($answer->question->$option)
                                <div class="p-3 rounded-3 mb-2 small d-flex align-items-center
                                    @if($isCorrectOption) bg-success bg-opacity-10 text-success border border-success border-opacity-25
                                    @elseif($isSelectedOption && !$isCorrectOption) bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25
                                    @else bg-light bg-opacity-5 text-muted @endif">
                                    <span class="me-3">{{ chr(64 + $i) }}.</span>
                                    <span class="flex-grow-1">{{ $answer->question->$option }}</span>
                                    @if($isCorrectOption)
                                        <i class="bi bi-check-lg ms-2"></i>
                                    @endif
                                </div>
                            @endif
                        @endfor
                    </div>

                    @if($answer->question->explanation)
                        <div class="mt-3 p-3 bg-light bg-opacity-5 rounded-3 border-start border-primary border-3 small">
                            <strong class="text-accent d-block mb-1"><i class="bi bi-info-circle me-1"></i> Explanation</strong>
                            {{ $answer->question->explanation }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top border-light border-opacity-10">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4 prev-review" {{ $index === 0 ? 'disabled' : '' }}>Previous</button>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 next-review" {{ $index === $attempt->answers->count() - 1 ? 'disabled' : '' }}>Next</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentReview = 0;
        const totalReviews = {{ $attempt->answers->count() }};
        const cards = document.querySelectorAll('.review-card');
        const nextBtns = document.querySelectorAll('.next-review');
        const prevBtns = document.querySelectorAll('.prev-review');

        nextBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentReview < totalReviews - 1) {
                    cards[currentReview].style.display = 'none';
                    currentReview++;
                    cards[currentReview].style.display = 'block';
                }
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentReview > 0) {
                    cards[currentReview].style.display = 'none';
                    currentReview--;
                    cards[currentReview].style.display = 'block';
                }
            });
        });
    });
</script>
@endpush
@endsection
