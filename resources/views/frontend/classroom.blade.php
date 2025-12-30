@extends('frontend.layouts.app')

@section('title', $course->title . ' - Classroom')

@push('styles')
<style>
    :root {
        --classroom-sidebar-width: 380px;
        --emerald-glow: 0 0 20px rgba(16, 185, 129, 0.2);
        --glass-bg: rgba(22, 27, 34, 0.7);
        --glass-border: rgba(48, 54, 61, 0.6);
    }

    /* Fancybox Customization */
    .fancybox__container {
        --fancybox-bg: rgba(6, 9, 15, 0.95);
    }
    
    .fancybox__toolbar {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
    }

    .image-zoom-container {
        position: relative;
        cursor: zoom-in;
        transition: transform 0.3s ease;
    }

    .image-zoom-container:hover {
        transform: scale(1.01);
    }

    .zoom-hint {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(16, 185, 129, 0.9);
        color: #000;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .image-zoom-container:hover .zoom-hint {
        opacity: 1;
    }

    /* Forcefully prevent selection on everything */
    * {
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
        -webkit-user-drag: none;
    }

    body {
        background-color: #06090f;
        color: #c9d1d9;
        overflow: hidden;
    }

    /* Blur content when focused out (helps prevent some screenshot tools) */
    .classroom-wrapper.content-protected {
        filter: blur(20px);
        pointer-events: none;
    }

    /* Layout */
    .classroom-wrapper {
        display: flex;
        height: calc(100vh - 80px);
        position: relative;
        background: #06090f;
    }

    /* Hide footer on classroom page */
    footer, .footer {
        display: none !important;
    }

    /* Sidebar Styles */
    .classroom-sidebar {
        width: var(--classroom-sidebar-width);
        background: #0d1117;
        border-right: 1px solid var(--glass-border);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        z-index: 100;
    }

    .sidebar-header {
        padding: 1.75rem 1.5rem;
        background: rgba(13, 17, 23, 0.95);
        border-bottom: 1px solid var(--glass-border);
    }

    .course-progress-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .search-box {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid var(--glass-border);
    }

    .search-input {
        background: #161b22;
        border: 1px solid var(--glass-border);
        color: #fff;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        width: 100%;
        transition: all 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: var(--emerald-glow);
    }

    .sidebar-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
    }

    /* Material Grouping */
    .material-group {
        margin-bottom: 1.5rem;
    }

    .group-label {
        padding: 0 1.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #8b949e;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
    }

    .group-label i {
        margin-right: 0.5rem;
        font-size: 0.9rem;
    }

    .material-item {
        padding: 0.65rem 1.25rem;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #8b949e;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 3px solid transparent;
        position: relative;
    }

    .material-item:hover {
        background: rgba(255, 255, 255, 0.04);
        color: #ffffff;
    }

    .material-item.active {
        background: rgba(16, 185, 129, 0.08);
        color: #10b981;
        border-left-color: #10b981;
    }

    .material-item .item-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: rgba(48, 54, 61, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.85rem;
        transition: all 0.2s;
        font-size: 1rem;
    }

    .material-item:hover .item-icon {
        background: rgba(48, 54, 61, 0.8);
        color: #fff;
    }

    .material-item.active .item-icon {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .item-title {
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* Content Area */
    .classroom-main {
        flex: 1;
        overflow-y: auto;
        padding: 3rem 2.5rem;
        background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.05), transparent 600px);
    }

    .content-max-width {
        max-width: 1100px;
        margin: 0 auto;
    }

    .viewer-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        border: 1px solid var(--glass-border);
        background: #000;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .video-aspect-ratio {
        position: relative;
        padding-top: 56.25%; /* 16:9 */
    }

    .video-aspect-ratio iframe, 
    .video-aspect-ratio video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .pdf-viewer {
        height: 700px;
        width: 100%;
    }

    /* Typography & Cards */
    .lesson-meta-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.5rem 2rem;
    }

    .lesson-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
        letter-spacing: 0.5px;
    }

    .badge-video { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .badge-pdf { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
    .badge-url { background: rgba(249, 115, 22, 0.15); color: #fb923c; }

    .lesson-title-main {
        font-family: 'Outfit', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .description-text {
        color: #8b949e;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    /* Navigation Buttons */
    .nav-controls {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        gap: 1rem;
    }

    .nav-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.8rem 1.8rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-prev {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border: 1px solid var(--glass-border);
    }

    .btn-prev:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
        transform: translateX(-5px);
    }

    .btn-next {
        background: #10b981;
        color: #000;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-next:hover {
        background: #059669;
        color: #000;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        transform: translateX(5px);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #30363d; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #10b981; }

    @media (max-width: 992px) {
        .classroom-wrapper { flex-direction: column; height: auto; overflow: visible; }
        .classroom-sidebar { width: 100%; height: auto; border-right: none; border-bottom: 1px solid var(--glass-border); position: relative; }
        .sidebar-scroll { max-height: 400px; }
        .classroom-main { height: auto; }
        body { overflow: auto; }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endpush

@section('content')
<div class="classroom-wrapper">
    <!-- Sidebar -->
    <aside class="classroom-sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('student.dashboard') }}" class="text-muted text-decoration-none me-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h6 class="text-white fw-bold mb-0 text-truncate">{{ $course->title }}</h6>
            </div>
            <div class="course-progress-card py-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted fw-bold" style="font-size: 10px;">PROGRESS</small>
                    <small class="text-emerald fw-bold" style="color: #10b981; font-size: 10px;">{{ $progress }}%</small>
                </div>
                <div class="progress" style="height: 4px; background: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-success" style="width: {{ $progress }}%; border-radius: 10px; background-color: #10b981 !important;"></div>
                </div>
            </div>
        </div>

        @if($course->live_class && $course->live_class_link)
        <div class="px-4 py-2" style="background: rgba(239, 68, 68, 0.1); border-bottom: 1px solid rgba(239, 68, 68, 0.2);">
            <a href="{{ $course->live_class_link }}" target="_blank" 
               class="btn btn-danger btn-sm w-100 fw-bold d-flex align-items-center justify-content-center py-2" 
               style="border-radius: 8px; font-size: 0.8rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);">
                <i class="bi bi-broadcast me-2"></i> JOIN LIVE NOW
            </a>
        </div>
        @endif

        <div class="search-box">
            <input type="text" class="search-input" id="materialSearch" placeholder="Search lessons, docs...">
        </div>

        <div class="sidebar-scroll" id="materialList">
            <!-- Group: Video Lectures -->
            @php $videos = $materials->where('material_type', 'video'); @endphp
            @if($videos->count() > 0)
            <div class="material-group" data-group="video">
                <div class="group-label"><i class="bi bi-play-circle-fill"></i> Video Lessons</div>
                @foreach($videos as $material)
                    <a href="{{ route('student.courses.classroom', ['slug' => $course->slug, 'lesson' => $material->id]) }}" 
                       class="material-item {{ $currentMaterial && $currentMaterial->id == $material->id ? 'active' : '' }}"
                       data-title="{{ strtolower($material->title) }}">
                        <div class="item-icon"><i class="bi bi-play-fill"></i></div>
                        <div class="item-title">{{ $material->title }}</div>
                    </a>
                @endforeach
            </div>
            @endif

            <!-- Group: Study Material -->
            @php $docs = $materials->whereIn('material_type', ['pdf', 'image', 'url']); @endphp
            @if($docs->count() > 0)
            <div class="material-group" data-group="resources">
                <div class="group-label"><i class="bi bi-files"></i> Study Material & Notes</div>
                @foreach($docs as $material)
                    <a href="{{ route('student.courses.classroom', ['slug' => $course->slug, 'lesson' => $material->id]) }}" 
                       class="material-item {{ $currentMaterial && $currentMaterial->id == $material->id ? 'active' : '' }}"
                       data-title="{{ strtolower($material->title) }}">
                        <div class="item-icon">
                            <i class="bi bi-{{ $material->material_type == 'pdf' ? 'file-earmark-pdf' : ($material->material_type == 'image' ? 'image' : 'link-45deg') }}"></i>
                        </div>
                        <div class="item-title">{{ $material->title }}</div>
                    </a>
                @endforeach
            </div>
            @endif

            <!-- Group: Quizzes -->
            @if($course->quizzes->count() > 0)
            <div class="material-group" data-group="quizzes">
                <div class="group-label"><i class="bi bi-question-diamond-fill"></i> Knowledge Check</div>
                @foreach($course->quizzes as $quiz)
                    <a href="{{ route('student.quizzes.index', $course) }}" class="material-item" data-title="{{ strtolower($quiz->title) }}">
                        <div class="item-icon"><i class="bi bi-pencil-square"></i></div>
                        <div class="item-title">{{ $quiz->title }}</div>
                    </a>
                @endforeach
            </div>
            @endif
        </div>
    </aside>

    <!-- Main Content -->
    <main class="classroom-main">
        <div class="content-max-width">
            @if($currentMaterial)
                <!-- Viewer -->
                <div class="viewer-container">
                    @if($currentMaterial->material_type == 'video')
                        <div class="video-aspect-ratio">
                            @if($currentMaterial->url)
                                @php
                                    $videoId = '';
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $currentMaterial->url, $match)) {
                                        $videoId = $match[1];
                                    }
                                @endphp
                                @if($videoId)<iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1&controls=1&fs=0&disablekb=1&iv_load_policy=3&playsinline=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @else
                                    <iframe src="{{ $currentMaterial->url }}" frameborder="0" allowfullscreen></iframe>
                                @endif
                            @elseif($currentMaterial->file_path)
                                <video controls controlsList="nodownload" class="w-100 h-100">
                                    <source src="{{ asset('storage/' . $currentMaterial->file_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    @elseif($currentMaterial->material_type == 'pdf')
                        <div class="pdf-viewer">
                             <iframe src="{{ asset('storage/' . $currentMaterial->file_path) }}#toolbar=0" width="100%" height="100%" style="border: none;"></iframe>
                        </div>
                    @elseif($currentMaterial->material_type == 'image')
                        <div class="text-center p-4 d-flex align-items-center justify-content-center" style="min-height: 600px; background: radial-gradient(circle, rgba(22, 27, 34, 0.5) 0%, rgba(6, 9, 15, 0.8) 100%); border-radius: 12px;">
                            <div class="image-zoom-container" data-fancybox="gallery" data-src="{{ asset('storage/' . $currentMaterial->file_path) }}" data-caption="{{ $currentMaterial->title }}">
                                <img src="{{ asset('storage/' . $currentMaterial->file_path) }}" class="img-fluid rounded-3 shadow-lg border border-secondary border-opacity-10" style="max-height: 550px; width: auto; object-fit: contain;" alt="{{ $currentMaterial->title }}">
                                <div class="zoom-hint">
                                    <i class="bi bi-arrows-fullscreen"></i> CLICK TO ENLARGE
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-link-45deg display-1 text-emerald opacity-50" style="color: #10b981"></i>
                            </div>
                            <h3 class="text-white mb-3">External Resource</h3>
                            <p class="text-muted mb-4 mx-auto" style="max-width: 500px">This material is hosted on an external platform. Click the button below to open it in a new tab.</p>
                            <a href="{{ $currentMaterial->url }}" target="_blank" class="btn-next nav-btn">
                                Open Material <i class="bi bi-box-arrow-up-right ms-2"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Info Card -->
                <div class="lesson-meta-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="lesson-badge badge-{{ $currentMaterial->material_type }}">
                                <i class="bi bi-{{ $currentMaterial->material_type == 'video' ? 'play-circle' : ($currentMaterial->material_type == 'pdf' ? 'file-pdf' : 'link-45deg') }} me-2"></i>
                                {{ strtoupper($currentMaterial->material_type) }}
                            </span>
                            <h1 class="lesson-title-main mb-0">{{ $currentMaterial->title }}</h1>
                        </div>
                    </div>

                    <!-- Navigation Controls -->
                    <div class="nav-controls">
                        @if($previousMaterial)
                            <a href="{{ route('student.courses.classroom', ['slug' => $course->slug, 'lesson' => $previousMaterial->id]) }}" class="nav-btn btn-prev">
                                <i class="bi bi-chevron-left me-2"></i> Previous Lesson
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextMaterial)
                            <a href="{{ route('student.courses.classroom', ['slug' => $course->slug, 'lesson' => $nextMaterial->id]) }}" class="nav-btn btn-next">
                                Next Lesson <i class="bi bi-chevron-right ms-2"></i>
                            </a>
                        @else
                            <form action="{{ route('student.courses.finish', $course->slug) }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-btn btn-next" style="border: none;">
                                    Finish Course <i class="bi bi-trophy ms-2"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="d-flex flex-column align-items-center justify-content-center py-5 text-center mt-5">
                    <div class="p-5 rounded-circle bg-white bg-opacity-5 mb-4">
                        <i class="bi bi-journal-x display-1 text-muted"></i>
                    </div>
                    <h2 class="text-white">No Content Available</h2>
                    <p class="text-muted" style="max-width: 400px">There are currently no lessons or materials uploaded for this course. Please check back later or contact support.</p>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-light rounded-pill px-4 mt-3">Back to Dashboard</a>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Fancybox
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [],
                    right: ["iterateZoom", "close"],
                },
            },
            Images: {
                initialSize: "fit",
            },
        });

        const searchInput = document.getElementById('materialSearch');
        const items = document.querySelectorAll('.material-item');
        const groups = document.querySelectorAll('.material-group');

        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            
            groups.forEach(group => {
                let hasVisibleItems = false;
                const groupItems = group.querySelectorAll('.material-item');
                
                groupItems.forEach(item => {
                    const title = item.getAttribute('data-title');
                    if (title.includes(term)) {
                        item.style.display = 'flex';
                        hasVisibleItems = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                group.style.display = hasVisibleItems ? 'block' : 'none';
            });
        });

        // Auto-scroll to active item
        const activeItem = document.querySelector('.material-item.active');
        if (activeItem) {
            activeItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // --- Security Features: Prevent Copy & Screenshots ---
        
        const showSecurityPopup = (message) => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Security Alert',
                    text: message,
                    confirmButtonColor: '#10b981',
                    background: '#0d1117',
                    color: '#fff'
                });
            } else {
                alert(message);
            }
        };

        // Disable right click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            showSecurityPopup('Right-click is disabled to protect course content.');
        });

        // Disable keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
            if (
                e.keyCode === 123 || 
                (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || 
                (e.ctrlKey && e.keyCode === 85) ||
                (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 83)) // Ctrl+C, Ctrl+V, Ctrl+S
            ) {
                e.preventDefault();
                showSecurityPopup('Keyboard shortcuts for copying or inspection are disabled.');
                return false;
            }

            // PrintScreen Detection (Limited)
            if (e.key === 'PrintScreen') {
                navigator.clipboard.writeText('');
                showSecurityPopup('Screenshots are discouraged. Please focus on learning!');
            }
        });

        // Detect PrintScreen (Modern Browsers)
        window.addEventListener('keyup', function(e) {
            if (e.key === 'PrintScreen') {
                navigator.clipboard.writeText('');
                showSecurityPopup('Screenshots are disabled.');
            }
        });

        // Prevent Dragging images
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('dragstart', (e) => e.preventDefault());
        });

        // Prevent Copy/Cut events directly
        document.addEventListener('copy', (e) => {
            e.preventDefault();
            showSecurityPopup('Copying content is not allowed.');
        });

        document.addEventListener('cut', (e) => {
            e.preventDefault();
            showSecurityPopup('Cutting content is not allowed.');
        });

        // Detect window blur (when user switches tabs or uses screenshot tools)
        const wrapper = document.querySelector('.classroom-wrapper');
        
        window.addEventListener('blur', () => {
            wrapper.classList.add('content-protected');
        });

        window.addEventListener('focus', () => {
            wrapper.classList.remove('content-protected');
        });

        // Prevent printing
        window.onbeforeprint = () => {
            wrapper.classList.add('content-protected');
            return false;
        };
        window.onafterprint = () => {
            wrapper.classList.remove('content-protected');
        };
    });
</script>
@endpush
