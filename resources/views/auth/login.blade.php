@extends('frontend.layouts.app')

@section('title', 'Login | Educater')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card-glass p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="text-gradient-green mb-2">Welcome Back</h2>
                    <p class="text-muted">Sign in to continue your learning journey</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success bg-accent-dim text-accent border-accent-dim mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label text-white small fw-bold">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" placeholder="name@example.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1 small text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label text-white small fw-bold">PASSWORD</label>
                            @if (Route::has('password.request'))
                                <a class="small text-accent" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password" 
                                class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1 small text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input bg-transparent border-light" name="remember">
                            <label for="remember_me" class="form-check-label text-muted small">Remember me</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3">
                            Log In <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-accent fw-bold">Sign Up Today</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group-text {
        border-color: var(--border-light);
    }
    .form-control {
        border-color: var(--border-light) !important;
    }
    .form-control:focus {
        border-color: var(--accent-color) !important;
    }
    .bg-accent-dim {
        background: var(--accent-dim);
    }
    .card-glass {
        border: 1px solid var(--border-light);
        background: var(--bg-surface);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
</style>
@endsection
