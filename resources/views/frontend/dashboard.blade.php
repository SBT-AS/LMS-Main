@extends('frontend.layouts.app')

@section('title', 'Student Dashboard')

@push('styles')
<style>
    .dashboard-container {
        min-height: 80vh;
    }
    .theme-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        overflow: hidden;
    }
    .user-profile-header {
        background: linear-gradient(135deg, var(--accent-color) 0%, #0a58ca 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
    }
    .nav-link-dashboard {
        padding: 14px 25px;
        color: var(--text-body);
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        border-right: 4px solid transparent;
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    .nav-link-dashboard:hover, .nav-link-dashboard.active {
        background-color: rgba(255, 255, 255, 0.05);
        color: var(--accent-color);
        border-right-color: var(--accent-color);
    }
    .nav-link-dashboard i {
        width: 24px;
        margin-right: 12px;
        font-size: 1.2rem;
    }
    
    .stats-card {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease;
        background: var(--bg-surface);
        border: 1px solid var(--border-light);
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }
    
    .course-card-dashboard {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
        background: var(--bg-surface);
        border: 1px solid var(--border-light);
    }
    .course-card-dashboard:hover {
        transform: translateY(-5px);
        box-shadow: var(--hover-shadow);
    }
    .course-img-wrapper {
        position: relative;
        padding-top: 56.25%; 
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.05);
    }
    .course-img-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .course-card-dashboard:hover .course-img-wrapper img {
        transform: scale(1.05);
    }
    .welcome-banner {
        background: linear-gradient(120deg, var(--accent-color), #6610f2);
        color: white;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner-decoration {
        position: absolute;
        right: -20px;
        bottom: -50px;
        font-size: 15rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }
    .bg-light-custom {
        background-color: var(--bg-page);
    }
</style>
@endpush

@section('content')
<div class="dashboard-container py-5 bg-light-custom">
    <div class="container">
        <div class="row g-4">
            <!-- Left Sidebar -->
            <div class="col-lg-3">
                <div class="theme-card shadow-sm position-sticky" style="top: 100px; z-index: 10;">
                    <div class="user-profile-header">
                        <div class="position-relative d-inline-block">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="avatar-circle mx-auto mb-3 shadow rounded-circle object-fit-cover" 
                                     style="width: 80px; height: 80px; border: 3px solid rgba(255,255,255,0.2);">
                            @else
                                <div class="avatar-circle mx-auto mb-3 bg-white text-dark fw-bold d-flex align-items-center justify-content-center shadow" 
                                     style="width: 80px; height: 80px; font-size: 2.5rem; border-radius: 50%;">
                                     {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-white rounded-circle">
                                <span class="visually-hidden">Online</span>
                            </span>
                        </div>
                        <h5 class="fw-bold mb-1 text-white">{{ Auth::user()->name }}</h5>
                        <p class="mb-0 text-white-50 small">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="p-0 py-2">
                        <div class="d-flex flex-column">
                            <a href="{{ route('student.dashboard') }}" class="nav-link-dashboard active">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <a href="#my-courses" class="nav-link-dashboard">
                                <i class="bi bi-journal-album"></i> My Courses
                            </a>
                            <a href="{{ route('student.certificates.index') }}" class="nav-link-dashboard">
                                <i class="bi bi-award"></i> Certificates
                            </a>
                            <a href="{{ route('profile.edit') }}" class="nav-link-dashboard">
                                <i class="bi bi-gear"></i> Profile Settings
                            </a>
                            <div class="border-top my-2 mx-3 border-secondary border-opacity-10"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-menu-form">
                                @csrf
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-menu-form').submit();" class="nav-link-dashboard text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <!-- Welcome Banner -->
                <div class="welcome-banner p-5 mb-4 shadow-sm">
                    <i class="bi bi-rocket-takeoff-fill welcome-banner-decoration"></i>
                    <div class="position-relative z-1">
                        <h2 class="fw-bold mb-2">Hello, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                        <p class="mb-4 opacity-75 fs-5" style="max-width: 600px;">
                            Start your day with some new learning. You have made great progress!
                        </p>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted small text-uppercase fw-semibold mb-1">Enrolled Courses</h6>
                                    <h2 class="fw-bold mb-0 text-white">{{ $enrolledCourses->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted small text-uppercase fw-semibold mb-1">Certificates</h6>
                                    <h2 class="fw-bold mb-0 text-white">{{ $certificates->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card shadow-sm h-100 p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted small text-uppercase fw-semibold mb-1">Quiz Attempts</h6>
                                    <h2 class="fw-bold mb-0 text-white">{{ $quizAttempts->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Enrolled Courses -->
                <div class="d-flex justify-content-between align-items-end mb-4" id="my-courses">
                    <div>
                        <h4 class="fw-bold m-0 text-white">My Learning</h4>
                        <p class="text-muted small mb-0">Continue where you left off</p>
                    </div>
                </div>

                @if($enrolledCourses->count() > 0 || $freeCourses->count() > 0)
                    <div class="row g-4 mb-5">
                        {{-- Show enrolled courses --}}
                        @foreach($enrolledCourses as $course)
                            <div class="col-md-6 col-lg-4">
                                <div class="card course-card-dashboard shadow-sm h-100">
                                    <div class="course-img-wrapper">
                                        <a href="{{ route('student.courses.classroom', $course->slug) }}">
                                            @if($course->image)
                                                <img src="{{ asset('storage/courses/' . $course->image) }}" alt="{{ $course->title }}">
                                            @else
                                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                                                    alt="{{ $course->title ?? 'Course' }}">
                                            @endif
                                        </a>
                                        <div class="position-absolute top-0 end-0 m-2">
                                            @if($course->pivot->status == 'completed')
                                                <span class="badge bg-success text-white shadow-sm rounded-pill"><i class="bi bi-check-circle me-1"></i> Completed</span>
                                            @else
                                                <span class="badge bg-white text-dark shadow-sm rounded-pill"><i class="bi bi-clock me-1"></i> In Progress</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <a href="{{ route('student.courses.classroom', $course->slug) }}" class="text-decoration-none">
                                            <h6 class="fw-bold text-white text-truncate mb-2" title="{{ $course->title }}">{{ $course->title }}</h6>
                                        </a>
                                        <div class="d-flex align-items-center justify-content-between mb-3 text-muted">
                                            <small>35% Complete</small>
                                            <small><i class="bi bi-play-circle me-1"></i> {{ $course->videoMaterials()->count() }} Lessons</small>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px; border-radius: 3px; background-color: rgba(255, 255, 255, 0.1);">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 35%;" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex gap-2">
                                             <a href="{{ route('student.courses.classroom', $course->slug) }}" class="btn btn-primary flex-grow-1 rounded-pill py-2">Continue</a>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Show free courses not yet enrolled --}}
                        @foreach($freeCourses as $course)
                            @if(!in_array($course->id, $enrolledCourseIds))
                                <div class="col-md-6 col-lg-4">
                                    <div class="card course-card-dashboard shadow-sm h-100">
                                        <div class="course-img-wrapper">
                                            @if($course->image)
                                                <img src="{{ asset('storage/courses/' . $course->image) }}" alt="{{ $course->title }}">
                                            @else
                                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                                                    alt="{{ $course->title ?? 'Course' }}">
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-success shadow-sm rounded-pill">Free</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-4">
                                            <h6 class="fw-bold text-white text-truncate mb-2" title="{{ $course->title }}">{{ $course->title }}</h6>
                                            <p class="small text-muted mb-4 line-clamp-2" style="height: 40px; overflow: hidden;">{{ Str::limit($course->description, 70) }}</p>
                                            
                                            <form action="{{ route('student.courses.enroll', $course->slug) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                                                    <i class="bi bi-play-circle me-1"></i> Access Now
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 mb-5 card-glass">
                        <div class="mb-3">
                             <div class="d-inline-flex align-items-center justify-content-center bg-light bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                                <i class="bi bi-journal-plus text-primary fs-1"></i>
                             </div>
                        </div>
                        <h4 class="fw-bold text-white">No courses enrolled yet</h4>
                        <p class="text-muted mb-4 col-md-8 mx-auto">It looks like you haven't started any courses yet. Explore our catalog to find the perfect course for you.</p>
                        <a href="{{ route('frontend.home') }}#courses" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-search me-2"></i> Browse Courses
                        </a>
                    </div>
                @endif

                <!-- Recent Quiz Attempts (Keeping this as well) -->
                @if($quizAttempts->count() > 0)
                    <div class="mb-5">
                        <h4 class="fw-bold mb-4 text-white">Recent Quiz Attempts</h4>
                        <div class="card stats-card p-0 overflow-hidden">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0">
                                    <thead class="bg-light bg-opacity-5">
                                        <tr>
                                            <th class="ps-4 py-3">Quiz</th>
                                            <th class="py-3">Course</th>
                                            <th class="py-3 text-center">Score</th>
                                            <th class="py-3">Date</th>
                                            <th class="pe-4 py-3 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quizAttempts as $attempt)
                                            <tr>
                                                <td class="ps-4">{{ $attempt->quiz->title }}</td>
                                                <td>{{ $attempt->quiz->course->title }}</td>
                                                <td class="text-center">
                                                    <span class="badge {{ $attempt->score >= 70 ? 'bg-success' : 'bg-warning' }}">
                                                        {{ number_format($attempt->score, 0) }}%
                                                    </span>
                                                </td>
                                                <td>{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : 'In progress' }}</td>
                                                <td class="pe-4 text-end">
                                                    <a href="{{ route('student.quizzes.result', [$attempt->quiz->course, $attempt->quiz, $attempt]) }}" 
                                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                        Results
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
