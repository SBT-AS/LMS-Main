@extends('frontend.layouts.app')

@section('title', $course->title . ' - Educater')

@push('styles')
<style>
    :root {
        --course-accent: #00dc82;
        --course-gradient: linear-gradient(135deg, #02040a 0%, #0b1215 100%);
        --glass-panel: rgba(255, 255, 255, 0.03);
    }

    .hero-section {
        background: var(--course-gradient);
        position: relative;
        padding: 100px 0 80px;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(0, 220, 130, 0.1) 0%, transparent 70%);
        z-index: 0;
    }

    .course-thumbnail-wrapper {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .course-thumbnail-wrapper img {
        transition: transform 0.5s ease;
    }

    .course-thumbnail-wrapper:hover img {
        transform: scale(1.05);
    }

    .content-card-premium {
        background: var(--glass-panel);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .content-card-premium:hover {
        border-color: rgba(0, 220, 130, 0.3);
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.5);
    }

    .sticky-sidebar {
        position: sticky;
        top: 100px;
        z-index: 10;
    }

    .price-badge {
        font-size: 2.5rem;
        font-weight: 800;
        color: #fff;
        display: block;
        margin-bottom: 1.5rem;
    }

    /* Lesson List Styling */
    .lesson-row-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.2rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.02);
        margin-bottom: 0.8rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.2s ease;
        text-decoration: none;
        color: var(--text-body);
    }

    .lesson-row-item:hover {
        background: rgba(0, 220, 130, 0.08);
        border-color: rgba(0, 220, 130, 0.3);
        color: #fff;
        transform: translateX(5px);
    }

    .lesson-icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: var(--course-accent);
    }

    .meta-tag {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #8b949e;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        color: #cbd5e1;
    }

    .benefit-item i {
        color: var(--course-accent);
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    @media (max-width: 991px) {
        .hero-section { padding: 60px 0; }
        .sticky-sidebar { position: static; margin-bottom: 2rem; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}" class="text-muted">Home</a></li>
                        <li class="breadcrumb-item active text-accent" aria-current="page">{{ $course->category->name ?? 'Course' }}</li>
                    </ol>
                </nav>
                
                <h1 class="display-3 fw-bold mb-4 text-gradient">
                    {{ $course->title }}
                </h1>
                
                <p class="lead text-body mb-5 opacity-75" style="font-size: 1.2rem; line-height: 1.8;">
                    {{ \Illuminate\Support\Str::limit($course->description, 200) }}
                </p>

                <div class="d-flex flex-wrap gap-4 mb-5">
                    <div class="meta-tag">
                        <i class="bi bi-play-circle-fill"></i>
                        {{ $course->videoMaterials()->count() }} Lessons
                    </div>
                    <div class="meta-tag">
                        <i class="bi bi-clock-fill"></i>
                        {{ $course->duration ?? 'Lifetime' }}
                    </div>
                    <div class="meta-tag">
                        <i class="bi bi-star-fill text-warning"></i>
                        {{ number_format($course->rating ?? 4.8, 1) }} Review
                    </div>
                    <div class="meta-tag">
                        <i class="bi bi-people-fill"></i>
                        Active Community
                    </div>
                </div>

                @if($isEnrolled)
                    <div class="d-flex gap-3 align-items-center">
                        <a href="{{ route('student.courses.classroom', $course->slug) }}" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-rocket-takeoff me-2"></i> Go to Classroom
                        </a>
                        <span class="text-success small fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> You are enrolled
                        </span>
                    </div>
                @else
                    <div class="d-flex gap-3">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-cart-plus me-2"></i> Get Started Now
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="col-lg-5">
                <div class="course-thumbnail-wrapper">
                    @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="img-fluid w-100">
                    @else
                        <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&q=80" alt="{{ $course->title }}" class="img-fluid w-100">
                    @endif
                    <div class="play-overlay">
                        <div class="btn-play">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Course Details Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Left Content -->
            <div class="col-lg-8">
                <!-- What You'll Learn -->
                <div class="content-card-premium">
                    <h3 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="bi bi-stars text-accent me-3"></i> What's inside this course?
                    </h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="benefit-item">
                                <i class="bi bi-check2-circle"></i> Complete foundation of the topic
                            </div>
                            <div class="benefit-item">
                                <i class="bi bi-check2-circle"></i> Hands-on practical projects
                            </div>
                            <div class="benefit-item">
                                <i class="bi bi-check2-circle"></i> Industry-ready architectures
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="benefit-item">
                                <i class="bi bi-check2-circle"></i> Expert feedback & support
                            </div>
                            <div class="benefit-item">
                                <i class="bi bi-check2-circle"></i> Portfolio-building exercises
                            </div>
                            <div class="benefit-item">
                                <i class="bi bi-check2-circle"></i> Digital completion certificate
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Description -->
                <div class="content-card-premium">
                    <h3 class="fw-bold mb-4">About this Course</h3>
                    <div class="description-text opacity-75">
                        {!! nl2br(e($course->description)) !!}
                    </div>
                </div>

                <!-- Course Content / Curriculum -->
                @if($course->materials->count() > 0)
                <div class="content-card-premium">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Course Curriculum</h3>
                        <span class="badge bg-white bg-opacity-10 rounded-pill px-3">{{ $course->materials->count() }} Items</span>
                    </div>

                    <!-- Video Group -->
                    @php $videos = $course->materials->where('material_type', 'video'); @endphp
                    @if($videos->count() > 0)
                        <h6 class="text-accent small fw-bold text-uppercase mb-3 mt-4">Video Lectures</h6>
                        @foreach($videos as $material)
                            <a href="{{ $isEnrolled ? route('student.courses.classroom', ['slug' => $course->slug, 'lesson' => $material->id]) : 'javascript:void(0)' }}" 
                               class="lesson-row-item {{ !$isEnrolled ? 'opacity-75' : '' }}">
                                <div class="lesson-icon-circle">
                                    <i class="bi bi-play-fill fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ $material->title }}</span>
                                    <span class="small opacity-50">Video Module</span>
                                </div>
                                @if(!$isEnrolled)
                                    <i class="bi bi-lock-fill ms-auto opacity-50"></i>
                                @endif
                            </a>
                        @endforeach
                    @endif

                    <!-- Resources Group -->
                    @php $resources = $course->materials->whereIn('material_type', ['pdf', 'image', 'url']); @endphp
                    @if($resources->count() > 0)
                        <h6 class="text-accent small fw-bold text-uppercase mb-3 mt-4">Resources & Notes</h6>
                        @foreach($resources as $material)
                            <a href="{{ $isEnrolled ? route('student.courses.classroom', ['slug' => $course->slug, 'lesson' => $material->id]) : 'javascript:void(0)' }}" 
                               class="lesson-row-item {{ !$isEnrolled ? 'opacity-75' : '' }}">
                                <div class="lesson-icon-circle">
                                    <i class="bi bi-{{ $material->material_type == 'pdf' ? 'file-pdf' : ($material->material_type == 'image' ? 'image' : 'link-45deg') }} fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ $material->title }}</span>
                                    <span class="small opacity-50 text-capitalize">{{ $material->material_type }} file</span>
                                </div>
                                @if(!$isEnrolled)
                                    <i class="bi bi-lock-fill ms-auto opacity-50"></i>
                                @endif
                            </a>
                        @endforeach
                    @endif
                </div>
                @endif

                <!-- Quizzes -->
                @if($course->quizzes->count() > 0)
                <div class="content-card-premium">
                    <h3 class="fw-bold mb-4">Assessments</h3>
                    @foreach($course->quizzes as $quiz)
                        <div class="lesson-row-item {{ !$isEnrolled ? 'opacity-75' : '' }}">
                            <div class="lesson-icon-circle">
                                <i class="bi bi-patch-check fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">{{ $quiz->title }}</span>
                                <span class="small opacity-50">{{ $quiz->questions()->count() }} Questions</span>
                            </div>
                            @if($isEnrolled)
                                <a href="{{ route('student.quizzes.index', $course) }}" class="btn btn-sm btn-outline-primary px-3">Take Now</a>
                            @else
                                <i class="bi bi-lock-fill ms-auto opacity-50"></i>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-sidebar">
                    <div class="content-card-premium">
                        @if(!$isEnrolled)
                            <h5 class="text-body opacity-75 mb-1">One-time Investment</h5>
                            <span class="price-badge">₹{{ number_format($course->price, 0) }}</span>
                            
                            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <button type="submit" class="btn btn-primary w-100 py-3 fs-5 shadow-lg">
                                    <i class="bi bi-lightning-charge-fill me-2"></i> Enroll Now
                                </button>
                            </form>
                            
                            <p class="text-center small opacity-50">30-Day Happiness Guarantee</p>
                            <hr class="border-secondary border-opacity-25 my-4">
                        @endif

                        <h6 class="fw-bold mb-3 text-white">This Enrollment Includes:</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="benefit-item"><i class="bi bi-play-circle"></i> On-demand Video content</li>
                            <li class="benefit-item"><i class="bi bi-file-earmark-arrow-down"></i> Downloadable resources</li>
                            <li class="benefit-item"><i class="bi bi-infinity"></i> Full lifetime access</li>
                            <li class="benefit-item"><i class="bi bi-phone"></i> Access on all devices</li>
                            <li class="benefit-item"><i class="bi bi-journal-check"></i> Quizzes & Assignments</li>
                            <li class="benefit-item"><i class="bi bi-award"></i> Verified Certificate</li>
                        </ul>
                    </div>

                    <!-- Secure Checkout Indicator -->
                    <div class="text-center p-3">
                        <div class="d-flex justify-content-center gap-3 opacity-50 grayscale">
                            <i class="bi bi-shield-lock-fill fs-3"></i>
                            <i class="bi bi-credit-card-2-back-fill fs-3"></i>
                            <i class="bi bi-patch-check-fill fs-3"></i>
                        </div>
                        <p class="small opacity-50 mt-2">100% Secure Checkout</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
