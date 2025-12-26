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
                    Modern <span class="text-gradient-green">Learning Platform</span><br>
                        <span class="text-gradient">Master Skills with Experts.</span>
                    </h1>
                </div>

                <p class="hero-subtitle mb-5 lead text-light">
                Learn Skills the Right Way – Hands-On, Project-Based, and Expert-Guided. Join 10,000+ Students Today.              </p>

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
    <p class="text-center small text-muted text-uppercase fw-bold mb-4 tracking-wide">
        Trusted by learners worldwide
    </p>

    <div class="marquee-container w-100 py-3 overflow-hidden position-relative">
        <div class="marquee-content d-flex gap-5 align-items-center">
            <h4>Programming</h4>
            <h4>Web Development</h4>
            <h4>Data Science</h4>
            <h4>Machine Learning</h4>
            <h4>UI/UX Design</h4>
            <h4>Cybersecurity</h4>
            <!-- Duplicate for smooth scroll -->
            <h4>Artificial Intelligence</h4>
            <h4>Mobile App Development</h4>
            <h4>Business & Entrepreneurship</h4>
            <h4>Finance & Accounting</h4>
            <h4>Personal Development</h4>
            <h4>Cybersecurity</h4>
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

                    <li class="step-item active">
                        <span class="step-number ">STEP 02</span>
                        <h4>Practice with Quizzes</h4>
                        <p>Test your knowledge with 500+ logic puzzles designed to reinforce your learning.</p>
                    </li>

                    <li class="step-item active">
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
            
            <div class="dropdown">
                @php
                    $selectedCategoryId = request('category_id');
                    $selectedCategoryName = 'All Categories';
                    if($selectedCategoryId) {
                        $found = $categories->firstWhere('id', $selectedCategoryId);
                        if($found) $selectedCategoryName = $found->name;
                    }
                @endphp
                
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="categoryDropdownBtn">
                    <i class="bi bi-funnel me-1"></i> <span id="selectedCategoryText">{{ $selectedCategoryName }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="background: var(--bg-surface); border: 1px solid var(--border-light);">
                    <li>
                        <a class="dropdown-item text-white category-filter" href="#" data-id="">
                            All Categories
                        </a>
                    </li>
                    <li><hr class="dropdown-divider" style="border-color: var(--border-light);"></li>
                    @foreach($categories as $cat)
                        <li>
                            <a class="dropdown-item text-white category-filter" href="#" data-id="{{ $cat->id }}" data-name="{{ $cat->name }}">
                                {{ $cat->name }}
                                <span class="badge bg-secondary ms-2" style="font-size: 0.7em;">{{ $cat->courses_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row g-4" id="courses-container">
            @include('frontend.partials.course-list')
        </div>
    </div>
</section>

<!-- Meet Our Mentors Section -->
<section class="mentors-pro-section py-5">
    <!-- ... (rest of the file content remains same until script) ... -->
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">
                Meet Your <span class="text-gradient-green">Expert Mentors</span>
            </h2>
            <p class="text-muted">
                Learn directly from industry professionals
            </p>
        </div>

        <div class="row g-4">

            <!-- Mentor 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="mentor-pro-card">
                    <div class="mentor-top">
                        <div class="mentor-img">
                            <img src="{{ asset('frontend/assets/mentors/mentor1.jpg') }}" alt="Mentor">
                        </div>
                    </div>

                    <div class="mentor-body text-center">
                        <h4>Michael Anderson</h4>
                        <span class="mentor-role">Senior Developer @ Google</span>

                        <p class="mentor-desc">
                            Expert in MERN stack, system design and scalable cloud solutions.
                        </p>

                        <div class="mentor-skills">
                            <span>React</span>
                            <span>Node.js</span>
                            <span>AWS</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mentor 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="mentor-pro-card">
                    <div class="mentor-top">
                        <div class="mentor-img">
                            <img src="{{ asset('frontend/assets/mentors/mentor2.jpg') }}" alt="Mentor">
                        </div>
                    </div>

                    <div class="mentor-body text-center">
                        <h4>Ryan Mitchell</h4>
                        <span class="mentor-role">AI Engineer @ Microsoft</span>

                        <p class="mentor-desc">
                            Specializes in AI, deep learning and real-world ML systems.
                        </p>

                        <div class="mentor-skills">
                            <span>Python</span>
                            <span>TensorFlow</span>
                            <span>AI / ML</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mentor 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="mentor-pro-card">
                    <div class="mentor-top">
                        <div class="mentor-img">
                            <img src="{{ asset('frontend/assets/mentors/mentor3.jpg') }}" alt="Mentor">
                        </div>
                    </div>

                    <div class="mentor-body text-center">
                        <h4>Rohit Kumar</h4>
                        <span class="mentor-role">Backend Engineer @ Amazon</span>

                        <p class="mentor-desc">
                            Backend & microservices expert with large scale systems.
                        </p>

                        <div class="mentor-skills">
                            <span>Java</span>
                            <span>Spring</span>
                            <span>Microservices</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


        <!-- Simple CTA -->
        <div class="text-center mt-4">
            <a href="#courses" class="btn btn-primary btn-lg px-5 py-3 shadow-lg rounded-pill fw-bold">
                <i class="bi bi-rocket-takeoff me-2"></i> Start Learning Now
            </a>
        </div>
    </div>

    <style>
       .mentors-pro-section {
    background: transparent;
}

/* Card */
.mentor-pro-card {
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.04),
        rgba(255,255,255,0.02)
    );
    border-radius: 22px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.08);
    transition: all 0.35s ease;
    height: 100%;
}

.mentor-pro-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 25px 50px rgba(0, 220, 130, 0.18);
}

/* Top Image */
.mentor-top {
    padding: 30px;
    display: flex;
    justify-content: center;
}

.mentor-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid rgba(0,220,130,0.4);
}

.mentor-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Body */
.mentor-body {
    padding: 0 28px 32px;
}

.mentor-body h4 {
    margin-bottom: 4px;
    font-weight: 600;
}

.mentor-role {
    display: block;
    font-size: 14px;
    color: #22c55e;
    margin-bottom: 14px;
}

.mentor-desc {
    font-size: 14px;
    color: #9ca3af;
    line-height: 1.6;
    margin-bottom: 18px;
}

/* Skills */
.mentor-skills span {
    display: inline-block;
    margin: 4px;
    padding: 6px 14px;
    font-size: 12px;
    border-radius: 999px;
    background: rgba(34,197,94,0.12);
    color: #22c55e;
    border: 1px solid rgba(34,197,94,0.35);
}

    </style>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryLinks = document.querySelectorAll('.category-filter');
    const coursesContainer = document.getElementById('courses-container');
    const dropdownBtnText = document.getElementById('selectedCategoryText');

    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const categoryId = this.dataset.id;
            const categoryName = this.dataset.name || 'All Categories';
            
            // Update UI immediately (Optimistic UI)
            dropdownBtnText.textContent = categoryName;
            
            // Add loading state
            coursesContainer.style.opacity = '0.5';
            
            // Fetch courses
            fetch(`{{ route('frontend.home') }}?category_id=${categoryId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                coursesContainer.innerHTML = html;
                coursesContainer.style.opacity = '1';
                
                // Re-initialize any javascript components if needed for new elements
                // e.g. tooltips, animations
                
                // Scroll to courses section smoothly if needed, but since it matches "without refresh" user might prefer staying put.
                // Optionally: document.getElementById('courses').scrollIntoView({behavior: 'smooth'});
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
                coursesContainer.style.opacity = '1';
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while loading courses!'
                });
            });
        });
    });
});
</script>
@endpush
@endsection
