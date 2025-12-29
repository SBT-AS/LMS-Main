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
            </ul>
            
            <div class="d-flex gap-3 align-items-center">
                @php
                    $cartCount = 0;
                    if (Auth::check()) {
                        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                    } else {
                        $cartCount = session()->has('cart') ? count(session('cart')) : 0;
                    }
                @endphp

                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative border-0 p-2">
                    <i class="bi bi-cart3 fs-5"></i>
                    @if($cartCount > 0)
                       <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-white fw-bold"
      style="background-color:#ff0000; font-size:0.7rem; min-width:18px; height:18px; display:flex; align-items:center; justify-content:center; transform: translate(-50%, -10%) !important;">
    {{ $cartCount }}
</span>

                    @endif
                </a>
                
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="background: var(--bg-surface); border: 1px solid var(--border-light);">
                            @if(!Auth::user()->hasRole('student'))
                                <li><a class="dropdown-item text-white" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-lock me-2"></i>Admin Panel
                                </a></li>
                            @endif
                            <li><hr class="dropdown-divider" style="border-color: var(--border-light);"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    @if(Auth::user()->hasRole('student'))
                                      <a href="{{ route('student.dashboard') }}" class="dropdown-item text-white">
                                        <i class="bi bi-person me-2"></i>Dashboard
                                    </a>
                                    @endif
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
