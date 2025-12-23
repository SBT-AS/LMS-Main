@extends('frontend.layouts.app')

@section('title', 'Home')
@section('meta_description', 'eLearning - Advance your engineering skills with world-class courses and mentors')

@section('content')

    <!-- ========== HERO SECTION ========== -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content">
                        <div class="badge-offer">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Get 30% off on first enroll</span>
                        </div>
                        <h1 class="hero-title">Advance your engineering skills with us.</h1>
                        <p class="hero-subtitle">Build skills with our courses and mentor from world-class companies.</p>

                        <!-- Search Box -->
                        <form class="search-box" action="{{ route('frontend.home') }}" method="GET">
                            <input type="text" name="search" class="form-control" placeholder="Search courses..." value="{{ request('search') }}">
                            <button class="btn-search" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        <!-- Features -->
                        <div class="hero-features">
                            <div class="feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Flexible</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Learning path</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Community</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hero-image">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Students Learning" class="img-fluid hero-img">
                        <div class="floating-card card-1">
                            <i class="bi bi-people-fill"></i>
                            <div>
                                <h4>10K+</h4>
                                <p>Students</p>
                            </div>
                        </div>
                        <div class="floating-card card-2">
                            <i class="bi bi-play-circle-fill"></i>
                            <div>
                                <h4>500+</h4>
                                <p>Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave SVG -->
        <div class="hero-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1"
                    d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>

    <!-- ========== COMPANIES SECTION ========== -->
    <section id="companies" class="companies-section">
        <div class="container">
            <p class="section-label text-center" data-aos="fade-up">Trusted by leading companies</p>
            <div class="companies-carousel owl-carousel" data-aos="fade-up">
                <div class="company-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google">
                </div>
                <div class="company-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft">
                </div>
                <div class="company-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="Amazon">
                </div>
                <div class="company-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" alt="Netflix">
                </div>
                <div class="company-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Tesla_logo.png" alt="Tesla">
                </div>
                <div class="company-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple">
                </div>
            </div>
        </div>
    </section>

    <!-- ========== COURSES SECTION ========== -->
    <section id="courses" class="courses-section">
        <div class="container">
            <div class="section-header mb-4" data-aos="fade-up">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 w-100">
                    <div>
                        <h2 class="section-title mb-0">Popular courses.</h2>
                        @if(request('search') || request('category'))
                            <p class="text-muted mt-2">
                                Found {{ $courses->total() }} courses 
                                @if(request('search')) matching "<strong>{{ request('search') }}</strong>"@endif
                                @if(request('category')) in selected category @endif
                                <a href="{{ route('frontend.home') }}" class="ms-2 text-primary small">Clear all</a>
                            </p>
                        @endif
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="category-filter-wrapper">
                        <form action="{{ route('frontend.home') }}" method="GET" id="categoryFilterForm" class="d-flex gap-2">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="category" class="form-select border-0 shadow-sm rounded-pill px-4 py-2" onchange="this.form.submit()" style="min-width: 200px; height: 50px;">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            @if(isset($courses) && $courses->count() > 0)
                <div class="{{ (request('search') || request('category')) ? 'row g-4' : 'courses-carousel owl-carousel' }}" data-aos="fade-up">
                    @foreach($courses as $course)
                        @if(request('search') || request('category'))
                            <div class="col-lg-4 col-md-6">
                                @include('frontend.components.course-card', ['course' => $course, 'enrolledCourseIds' => $enrolledCourseIds ?? []])
                            </div>
                        @else
                            @include('frontend.components.course-card', ['course' => $course, 'enrolledCourseIds' => $enrolledCourseIds ?? []])
                        @endif
                    @endforeach
                </div>
                
                @if(request('search') || request('category'))
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $courses->links() }}
                    </div>
                @endif
            @else
                <div class="col-12 text-center py-5" data-aos="fade-up">
                    <div class="empty-state">
                        <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-dark fw-bold">No courses found</h4>
                        <p class="text-muted">We couldn't find any courses matching your criteria. Try adjusting your search or filters.</p>
                        <a href="{{ route('frontend.home') }}" class="btn btn-primary rounded-pill px-4 mt-2">Browse All Courses</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- ========== MENTORS SECTION ========== -->
    <section id="mentors" class="mentors-section">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Meet with our <br>mentors.</h2>

            <div class="mentors-carousel owl-carousel" data-aos="fade-up">
                @if(isset($mentors) && $mentors->count() > 0)
                    @foreach($mentors as $mentor)
                        @include('frontend.components.mentor-card', ['mentor' => $mentor])
                    @endforeach
                @else
                    <!-- Static Demo Mentors -->
                    @include('frontend.components.mentor-card', [
                        'name' => 'John Smith',
                        'role' => 'Senior Software Engineer at Google',
                        'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                    ])
                    
                    @include('frontend.components.mentor-card', [
                        'name' => 'Emily Chen',
                        'role' => 'Data Science Lead at Microsoft',
                        'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                    ])
                    
                    @include('frontend.components.mentor-card', [
                        'name' => 'Michael Brown',
                        'role' => 'UX Designer at Apple',
                        'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                    ])
                    
                    @include('frontend.components.mentor-card', [
                        'name' => 'Sarah Wilson',
                        'role' => 'Product Manager at Meta',
                        'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                    ])
                    
                    @include('frontend.components.mentor-card', [
                        'name' => 'David Lee',
                        'role' => 'DevOps Engineer at Amazon',
                        'image' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                    ])
                @endif
            </div>
        </div>
    </section>

    <!-- ========== TESTIMONIALS SECTION ========== -->
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">What our students say</h2>

            <div class="testimonials-carousel owl-carousel" data-aos="fade-up">
                @if(isset($testimonials) && $testimonials->count() > 0)
                    @foreach($testimonials as $index => $testimonial)
                        @php
                            $shadows = ['pink', 'blue', 'purple'];
                            $shadow = $shadows[$index % 3];
                        @endphp
                        @include('frontend.components.testimonial-card', ['testimonial' => $testimonial, 'shadow' => $shadow])
                    @endforeach
                @else
                    <!-- Static Demo Testimonials -->
                    @include('frontend.components.testimonial-card', [
                        'name' => 'Jennifer Martinez',
                        'designation' => 'Frontend Developer',
                        'content' => 'This platform has completely transformed my career. The courses are well-structured and the mentors are incredibly supportive.',
                        'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                        'rating' => 5,
                        'shadow' => 'pink'
                    ])
                    
                    @include('frontend.components.testimonial-card', [
                        'name' => 'Robert Thompson',
                        'designation' => 'Software Engineer',
                        'content' => 'I went from zero coding knowledge to landing my dream job in just 6 months. Highly recommend this platform!',
                        'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                        'rating' => 4.5,
                        'shadow' => 'blue'
                    ])
                    
                    @include('frontend.components.testimonial-card', [
                        'name' => 'Amanda Foster',
                        'designation' => 'Data Analyst',
                        'content' => 'The quality of content is amazing. Each course is taught by industry experts who really know their stuff.',
                        'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                        'rating' => 5,
                        'shadow' => 'purple'
                    ])
                    
                    @include('frontend.components.testimonial-card', [
                        'name' => 'Daniel Garcia',
                        'designation' => 'Full Stack Developer',
                        'content' => 'The community support is fantastic. I love how students help each other and the mentors are always available.',
                        'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                        'rating' => 4.5,
                        'shadow' => 'pink'
                    ])
                @endif
            </div>
        </div>
    </section>

    <!-- ========== NEWSLETTER SECTION ========== -->
    <section id="contact" class="newsletter-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="newsletter-content">
                        <h2 class="newsletter-title">Subscribe to our newsletter</h2>
                        <p class="newsletter-text">Get the latest updates on new courses, exclusive offers, and learning
                            resources delivered straight to your inbox.</p>
                        <form class="newsletter-form" action="#" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                                <button class="btn btn-subscribe" type="submit">Subscribe</button>
                            </div>
                        </form>
                        <p class="newsletter-note"><i class="bi bi-shield-check"></i> We respect your privacy.
                            Unsubscribe at any time.</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="newsletter-image">
                        <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            alt="Newsletter" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========== QUICK VIEW MODAL ========== -->
    <div class="modal fade quick-view-modal" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content overflow-hidden border-0">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="modal-body p-0">
                    <div class="row g-0 h-100">
                        <!-- Left Side: Visuals -->
                        <div class="col-lg-5 position-relative bg-dark" style="min-height: 400px;">
                            <div class="quick-view-image h-100 w-100 position-relative">
                                <img src="" alt="Course Image" id="qv-image" class="w-100 h-100 object-fit-cover opacity-75">
                                <div class="qv-overlay-gradient" id="qv-overlay"></div>
                                <div class="qv-badges position-absolute top-0 start-0 p-4" style="z-index: 5;">
                                    <span class="badge bg-white text-dark fw-bold shadow-sm px-3 py-2 rounded-pill text-uppercase tracking-wide" style="font-size: 0.75rem;" id="qv-category">Development</span>
                                </div>
                                <div class="qv-play-button position-absolute top-50 start-50 translate-middle" id="qv-play-btn" style="z-index: 5;">
                                    <div class="play-icon-wrapper pulse-animation">
                                        <i class="bi bi-play-fill"></i>
                                    </div>
                                    <p class="text-white text-center mt-3 fw-medium small tracking-wide text-uppercase">Watch Preview</p>
                                </div>
                                <video id="qv-video" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0 d-none" controls style="z-index: 10;"></video>
                            </div>
                        </div>

                        <!-- Right Side: Content -->
                        <div class="col-lg-7">
                            <div class="quick-view-content p-4 p-lg-5 d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="rating-badge d-flex align-items-center gap-1 text-warning fw-bold">
                                        <i class="bi bi-star-fill"></i>
                                        <span id="qv-rating" class="text-dark">4.8</span>
                                        <span class="text-muted small fw-normal ms-1" id="qv-reviews">(120 reviews)</span>
                                    </div>
                                    <div class="price-tag text-end lh-1">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <span class="current h3 mb-0 fw-bold text-primary" id="qv-price">₹499</span>
                                        </div>
                                        <span class="original text-decoration-line-through text-muted small" id="qv-original-price">₹1999</span>
                                    </div>
                                </div>

                                <h2 class="modal-course-title fw-bold mb-3 h3 text-dark" id="qv-title">Course Title</h2>

                                <!-- Meta Pills -->
                                <div class="course-meta-pills d-flex flex-wrap gap-3 mb-4 text-muted small">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="bi bi-play-circle text-primary"></i> <span id="qv-lessons">12 Lessons</span>
                                    </span>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="bi bi-clock text-primary"></i> <span id="qv-duration">5h 30m</span>
                                    </span>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="bi bi-people text-primary"></i> <span id="qv-students">2.5k Students</span>
                                    </span>
                                </div>

                                <!-- Tabs Navigation -->
                                <ul class="nav nav-tabs nav-tabs-custom nav-fill mb-4" id="quickViewTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab" aria-controls="features" aria-selected="false">Curriculum</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="instructor-tab" data-bs-toggle="tab" data-bs-target="#instructor" type="button" role="tab" aria-controls="instructor" aria-selected="false">Instructor</button>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content flex-grow-1 overflow-auto custom-scrollbar pe-2" id="quickViewTabsContent" style="max-height: 200px;">
                                    <!-- Overview Tab -->
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                        <p class="text-secondary leading-relaxed mb-0" id="qv-description" style="line-height: 1.7;">
                                            Course description goes here...
                                        </p>
                                    </div>

                                    <!-- Features Tab -->
                                    <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                                        <ul class="feature-list-preview list-unstyled row g-2 mb-0" id="qv-features">
                                            <!-- JS will populate list -->
                                        </ul>
                                    </div>

                                    <!-- Instructor Tab -->
                                    <div class="tab-pane fade" id="instructor" role="tabpanel" aria-labelledby="instructor-tab">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <img src="" alt="Instructor" id="qv-instructor-img" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark" id="qv-instructor-name">John Doe</h6>
                                                <p class="mb-0 text-muted small">Lead Instructor</p>
                                            </div>
                                        </div>
                                        <p class="text-secondary small mb-0">
                                            Our instructors are industry experts with years of experience in their respective fields. They are dedicated to helping you master new skills and achieve your career goals.
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="action-buttons d-flex gap-3 mt-4 pt-3 border-top">
                                    <a href="javascript:void(0)" class="btn btn-primary btn-lg flex-grow-1 rounded-pill shadow-lg align-items-center justify-content-center d-flex gap-2 fw-semibold btn-add-to-cart" id="qv-btn-buy" data-id="">
                                        Enroll Now <i class="bi bi-arrow-right"></i>
                                    </a>
                                    <button class="btn btn-outline-secondary btn-lg btn-icon rounded-circle" title="Add to Wishlist">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/home.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('frontend/js/home.js') }}"></script>
@endpush
