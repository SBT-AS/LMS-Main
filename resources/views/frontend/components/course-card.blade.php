<div class="course-card">
    <!-- Visual -->
    <div class="course-media">
        @if($course->image)
            <img src="{{ asset('storage/courses/' . $course->image) }}" class="course-image" alt="{{ $course->title }}">
        @else
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&q=80" class="course-image" alt="{{ $course->title }}">
        @endif
        <!-- Play Overlay -->
        <div class="play-overlay">
            <a href="{{ route('student.courses.show', $course->slug) }}" class="btn-play">
                <i class="bi bi-play-fill"></i>
            </a>
        </div>
    </div>
    
    <!-- Body -->
    <div class="course-body">
        @if($course->category)
            <span class="badge-float">{{ $course->category->name }}</span>
        @endif
        
        <a href="{{ route('student.courses.show', $course->slug) }}" class="text-white">
            <h4>{{ $course->title }}</h4>
        </a>
        
        <p>{{ Str::limit($course->description, 100) }}</p>
        
        <!-- Meta Footer -->
        <div class="course-meta-row">
            <span>
                <i class="bi bi-book me-1"></i> 
                {{ $course->videoMaterials()->count() ?? 0 }} Lessons
            </span>
            <span>
                <i class="bi bi-star-fill text-warning me-1"></i> 
                {{ number_format($course->rating ?? 4.5, 1) }}
            </span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary border-opacity-10">
            <div class="course-price-tag">
                @if($course->price)
                    ₹{{ number_format($course->price, 0) }}
                @else
                    Free
                @endif
            </div>
            
            @auth
                @if(Auth::user()->courses->contains($course->id))
                    <span class="badge bg-success">Enrolled</span>
                @else
                    <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-cart-plus me-1"></i> Add
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-cart-plus me-1"></i> Add
                </a>
            @endauth
        </div>
    </div>
</div>
