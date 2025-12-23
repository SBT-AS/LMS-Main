<!-- ========== HEADER ========== -->
<header id="header" class="header @yield('header_class')">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('frontend.home') }}">
                <span class="logo-text">e<span class="logo-highlight">Learning</span></span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.home') }}#courses">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.home') }}#mentors">Mentors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.home') }}#contact">Contact</a>
                    </li>
                </ul>

                <!--cart btn-->
                <a href="{{ route('cart.index') }}" class="btn btn-cart">
                    <i class="bi bi-cart text-white"></i>
                    <span class="cart-count {{ (auth()->check() && auth()->user()->carts()->count() > 0) ? '' : 'd-none' }}">
                        {{ auth()->check() ? auth()->user()->carts()->count() : 0 }}
                    </span>
                </a>
               <!-- login signup btn -->
              <!-- login signup btn -->
<div class="nav-buttons d-flex gap-3 align-items-center">

    @auth
        {{-- ADMIN --}}
        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}"
               class="btn btn-dashboard btn-admin d-flex align-items-center gap-2 px-3 py-2">
                <i class="bi bi-speedometer2 fs-5"></i>
                <span>Admin Panel</span>
            </a>

        {{-- STUDENT --}}
        @elseif(auth()->user()->hasRole('student'))
            <a href="{{ route('student.dashboard') }}"
               class="btn btn-dashboard btn-student d-flex align-items-center gap-2 px-3 py-2">
                <i class="bi bi-mortarboard fs-5"></i>
                <span>My Dashboard</span>
            </a>
        @endif

        <!-- Logout Icon Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="btn btn-logout rounded-circle"
                title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>

    @else
        <!-- Guest Buttons -->
        <button class="btn btn-outline-primary px-3"
                data-bs-toggle="modal" data-bs-target="#signinModal">
            Sign In
        </button>
        <button class="btn btn-primary px-3"
                data-bs-toggle="modal" data-bs-target="#signupModal">
            Sign Up
        </button>
    @endauth
</div>


                </div>
                
            </div>
        </div>
    </nav>
</header>
