<footer>
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-4 col-md-12">
                <div class="footer-brand mb-4">
                    <h3 class="fw-bold text-white">Educater<span class="text-accent">.</span></h3>
                    <p>The #1 platform for project-based learning. We help you build the skills that matter.</p>
                </div>
                <div class="d-flex social-icons">
                    <a href="#" class="social-icon-circle"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon-circle"><i class="bi bi-github"></i></a>
                    <a href="#" class="social-icon-circle"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-icon-circle"><i class="bi bi-instagram"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <h5 class="footer-heading">Platform</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('frontend.home') }}#courses">Browse Courses</a></li>
                    @auth
                        <li><a href="{{ route('student.dashboard') }}">My Dashboard</a></li>
                        <li><a href="{{ route('student.certificates.index') }}">Certificates</a></li>
                    @endauth
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-6">
                <h5 class="footer-heading">Company</h5>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <h5 class="footer-heading">Stay Updated</h5>
                <p class="text-muted small mb-3">New courses and discounts sent to your inbox.</p>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Email address">
                    <button class="btn btn-primary px-3"><i class="bi bi-send-fill"></i></button>
                </form>
            </div>
        </div>

        <div class="border-top border-secondary border-opacity-10 mt-5 pt-4 d-flex justify-content-between flex-wrap gap-3 text-muted small">
            <span>&copy; {{ date('Y') }} Educater Inc. All rights reserved.</span>
            <div class="d-flex gap-3">
                <a href="#" class="text-muted text-decoration-none">Privacy</a>
                <a href="#" class="text-muted text-decoration-none">Terms</a>
            </div>
        </div>
    </div>
</footer>
