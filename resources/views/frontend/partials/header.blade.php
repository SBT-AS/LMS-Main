<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('frontend.home') }}">
            Educater<span class="text-accent">.</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon" style="filter: invert(1)"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.home') }}#courses">Courses</a>
                    
                </li>
                @auth
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('frontend.home') }}#footer">About</a>
                    </li>
                @endauth
            </ul>
            
            <div class="d-flex gap-3">
                @auth
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative border-0">
                        <i class="bi bi-cart3 fs-5"></i>
                        @if(session()->has('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-accent border border-light rounded-circle" 
                                  style="width:10px; height:10px; background:var(--accent-color)"></span>
                        @endif
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="background: var(--bg-surface); border: 1px solid var(--border-light);">
                            <li><a class="dropdown-item text-white" href="{{ route('student.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a></li>
                            <li><a class="dropdown-item text-white" href="{{ route('student.certificates.index') }}">
                                <i class="bi bi-award me-2"></i>Certificates
                            </a></li>
                            <li><hr class="dropdown-divider" style="border-color: var(--border-light);"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-white">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>   
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary shadow-lg">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
