@extends('frontend.layouts.app')

@section('title', 'Educater | Master Modern Engineering')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <div class="container position-relative z-2">
        <div class="row align-items-center">
            <!-- TEXT CONTENT -->
            <div class="col-12 col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                <div class="mb-4">
                    <span class="badge-float mb-3 text-accent border-accent" style="background: rgba(0, 220, 130, 0.1);">
                        <i class="bi bi-lightning-fill me-1"></i> NEW: Interactive Quizzes
                    </span>
                    <h1 class="hero-title animate-words">
                        Master <span class="text-gradient-green">Modern Engineering</span><br>
                        <span class="text-gradient">By Building Real Apps.</span>
                    </h1>
                </div>

                <p class="hero-subtitle mb-5 lead text-light">
                    Stop watching tutorials. Start writing code. Join 10,000+ developers building their future with our project-based curriculum.
                </p>

                <!-- Search Bar -->
                <div class="hero-cta position-relative d-inline-block w-100" style="max-width: 480px;">
                    <form action="#courses" class="d-flex align-items-center p-1 rounded-pill border border-light"
                          style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px);">
                        <input type="text" class="form-control border-0 shadow-none bg-transparent ps-4 text-white"
                               placeholder="What do you want to learn?" style="background: transparent !important;">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 m-1">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                    <!-- Floating badges -->
                    <div class="d-flex gap-4 mt-4 small text-light justify-content-center justify-content-lg-start">
                        <span><i class="bi bi-check-circle-fill text-accent me-1"></i> Certified Tutors</span>
                        <span><i class="bi bi-check-circle-fill text-accent me-1"></i> Lifetime Access</span>
                    </div>
                </div>
            </div>

            <!-- VISUAL -->
            <div class="col-12 col-lg-6 text-center position-relative">
                <!-- Glow effect behind image -->
                <div class="position-absolute top-50 start-50 translate-middle rounded-circle"
                     style="width: 300px; height: 300px; background: var(--accent-color); filter: blur(120px); opacity: 0.2; z-index: -1;"></div>
                <img src="{{ asset('frontend/assets/hero-image.svg') }}" alt="Hero" class="img-fluid position-relative z-2"
                     style="transform: scale(1.1);">
            </div>
        </div>
    </div>
</section>

<!-- TRUSTED BY SECTION -->
<div class="container pb-5 mb-5 border-bottom border-secondary border-opacity-10">
    <p class="text-center small text-muted text-uppercase fw-bold mb-4 tracking-wide">Trusted by engineering teams at</p>
    <!-- Marquee scrolling logos -->
    <div class="marquee-container w-100 py-3">
        <div class="marquee-content">
            <!-- Set 1 -->
            <h4>Google</h4>
            <h4>Microsoft</h4>
            <h4>Spotify</h4>
            <h4>Amazon</h4>
            <h4>Netflix</h4>
            <h4>Meta</h4>
            <h4>Uber</h4>
            <h4>Airbnb</h4>
            <!-- Set 2 (Duplicate for seamless scroll) -->
            <h4>Google</h4>
            <h4>Microsoft</h4>
            <h4>Spotify</h4>
            <h4>Amazon</h4>
            <h4>Netflix</h4>
            <h4>Meta</h4>
            <h4>Uber</h4>
            <h4>Airbnb</h4>
        </div>
    </div>
</div>

<!-- How it works -->
<section class="steps-section py-5 position-relative" id="how-it-works">
    <!-- Ambient Glow -->
    <div class="position-absolute top-50 start-0 translate-middle-y rounded-circle"
         style="width: 400px; height: 400px; background: radial-gradient(circle, rgba(0,220,130,0.15) 0%, transparent 70%); filter: blur(60px); z-index: -1;"></div>

    <div class="container position-relative z-1">
        <div class="text-center mb-5">
            <h2 class="mb-3">How you'll master code</h2>
            <p class="text-muted mx-auto" style="max-width: 500px">Our unique "Learn by Doing" methodology ensures you retain what you learn.</p>
        </div>

        <div class="row align-items-center">
            <!-- LEFT: STEPS -->
            <div class="col-12 col-lg-6">
                <ul class="steps-list">
                    <li class="step-item active">
                        <span class="step-number">STEP 01</span>
                        <h4>Learn Core Concepts</h4>
                        <p>Deep dive into theory with interactive diagrams that bring complex topics to life.</p>
                    </li>

                    <li class="step-item">
                        <span class="step-number">STEP 02</span>
                        <h4>Practice with Quizzes</h4>
                        <p>Test your knowledge with 500+ logic puzzles designed to reinforce your learning.</p>
                    </li>

                    <li class="step-item">
                        <span class="step-number">STEP 03</span>
                        <h4>Build Real Projects</h4>
                        <p>Create production-ready apps for your portfolio that prove your skills to employers.</p>
                    </li>
                </ul>
            </div>

            <!-- RIGHT: VISUAL -->
            <div class="col-12 col-lg-6 mt-4 mt-lg-0 text-center">
                <div class="step-visual card-glass p-3">
                    <img src="{{ asset('frontend/assets/step-1.svg') }}" alt="Step visual" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Course preview -->
<section class="courses-preview-section py-5" id="courses">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="section-title mb-2">Featured Courses</h2>
                <p class="section-subtitle text-muted m-0">
                    Hand-picked selections to get you hired.
                </p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($featuredCourses as $course)
                <div class="col-md-6 col-lg-4">
                    @include('frontend.components.course-card', ['course' => $course])
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">No courses available yet. Check back soon!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Meet Our Mentors Section -->
<section class="mentors-section position-relative overflow-hidden py-5">
    <div class="container position-relative" style="z-index: 1;">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">
                Meet Your <span class="text-gradient-green">Expert Mentors</span>
            </h2>
            <p class="text-muted mx-auto" style="max-width: 550px;">
                Learn from industry veterans with real-world experience.
            </p>
        </div>

        <!-- Mentors Grid -->
        <div class="row g-4 justify-content-center mb-4">
            <!-- Mentor 1 -->
            <div class="col-md-6 col-lg-5">
                <div class="card-glass p-4 text-center h-100 mentor-card" style="transition: all 0.3s ease;">
                    <div class="position-relative d-inline-block mb-3">
                        <div style="width: 90px; height: 90px; background: linear-gradient(135deg, #00dc82, #38bdf8); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 8px 20px rgba(0, 220, 130, 0.3);">
                            <span style="font-size: 2rem; font-weight: bold; color: #000;">AS</span>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">Aarav Sharma</h4>
                    <p class="text-accent small mb-3">Senior Developer @ Google</p>
                    <p class="text-body small text-muted mb-3">Expert in MERN stack, system design, and cloud architecture.</p>

                    <!-- Expertise Tags -->
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small">React</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small">Node.js</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small">AWS</span>
                    </div>
                </div>
            </div>

            <!-- Mentor 2 -->
            <div class="col-md-6 col-lg-5">
                <div class="card-glass p-4 text-center h-100 mentor-card" style="transition: all 0.3s ease;">
                    <div class="position-relative d-inline-block mb-3">
                        <div style="width: 90px; height: 90px; background: linear-gradient(135deg, #8b5cf6, #ec4899); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);">
                            <span style="font-size: 2rem; font-weight: bold; color: #fff;">PS</span>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">Priya Singh</h4>
                    <p class="text-accent small mb-3">AI/ML Engineer @ Microsoft</p>
                    <p class="text-body small text-muted mb-3">Specializes in deep learning and natural language processing.</p>

                    <!-- Expertise Tags -->
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small">Python</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small">TensorFlow</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small">AI/ML</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simple CTA -->
        <div class="text-center mt-4">
            <a href="#courses" class="btn btn-primary btn-lg px-5 py-3 shadow-lg rounded-pill fw-bold">
                <i class="bi bi-rocket-takeoff me-2"></i> Start Learning Now
            </a>
        </div>
    </div>

    <style>
        .mentor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 220, 130, 0.2);
        }
    </style>
</section>
@endsection
