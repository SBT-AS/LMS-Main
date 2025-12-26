@forelse($featuredCourses as $course)
    <div class="col-md-6 col-lg-4 animate-up">
        @include('frontend.components.course-card', ['course' => $course])
    </div>
@empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-3">No courses available for this category yet.</p>
        </div>
    </div>
@endforelse
