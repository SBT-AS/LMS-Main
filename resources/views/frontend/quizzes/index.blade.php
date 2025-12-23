@extends('frontend.layouts.app')

@section('title', 'Quizzes - ' . $course->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('student.courses.classroom', $course->slug) }}" class="btn btn-outline-secondary rounded-pill me-3">
                    <i class="bi bi-arrow-left"></i> Back to Classroom
                </a>
                <h2 class="fw-bold mb-0">Course Quizzes</h2>
            </div>

            <div class="card-glass p-0 overflow-hidden mb-4">
                <div class="p-4 border-bottom border-light border-opacity-10">
                    <h5 class="fw-bold mb-1">{{ $course->title }}</h5>
                    <p class="text-muted small mb-0">Test your knowledge with these quizzes</p>
                </div>

                <div class="list-group list-group-flush">
                    @forelse($quizzes as $quiz)
                        <div class="list-group-item bg-transparent p-4 border-light border-opacity-10">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="fw-bold mb-1">{{ $quiz->title }}</h5>
                                    <div class="d-flex gap-3 text-muted small">
                                        <span><i class="bi bi-question-circle me-1"></i> {{ $quiz->questions->count() }} Questions</span>
                                        <span><i class="bi bi-clock me-1"></i> {{ $quiz->duration }} Minutes</span>
                                        @if($quiz->best_score !== null)
                                            <span class="text-success"><i class="bi bi-trophy me-1"></i> Best Score: {{ $quiz->best_score }}%</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <form action="{{ route('student.quizzes.start', [$course->id, $quiz->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            {{ $quiz->best_score !== null ? 'Retake Quiz' : 'Start Quiz' }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($quiz->userAttempts->count() > 0)
                                <div class="mt-3 p-3 rounded bg-light bg-opacity-5">
                                    <h6 class="small fw-bold text-muted text-uppercase mb-2">Previous Attempts</h6>
                                    @foreach($quiz->userAttempts->take(3) as $attempt)
                                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                                            <span>{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : 'Incomplete' }}</span>
                                            <span class="fw-bold {{ $attempt->score >= 70 ? 'text-success' : 'text-warning' }}">
                                                {{ $attempt->score }}%
                                            </span>
                                            <a href="{{ route('student.quizzes.result', [$course->id, $quiz->id, $attempt->id]) }}" class="text-accent text-decoration-none">View Result</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-5 text-center">
                            <p class="text-muted">No quizzes available for this course yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
