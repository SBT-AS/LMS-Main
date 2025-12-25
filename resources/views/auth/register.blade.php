@extends('frontend.layouts.app')

@section('title', 'Register | Educater')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-6">
            <div class="card-glass p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="text-gradient-green mb-2">Join the Future</h2>
                    <p class="text-muted">Create your account to start learning from industry experts</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label text-white small fw-bold">FULL NAME</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                <i class="bi bi-person"></i>
                            </span>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                                class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" placeholder="John Doe">
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block mt-1 small text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label text-white small fw-bold">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" placeholder="name@example.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1 small text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <!-- Password -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label for="password" class="form-label text-white small fw-bold">PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input id="password" type="password" name="password" required autocomplete="new-password" 
                                    class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" placeholder="••••••••">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block mt-1 small text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label text-white small fw-bold">CONFIRM PASS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                                    class="form-control border-start-0 ps-0" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input id="terms" type="checkbox" class="form-check-input bg-transparent border-light" required>
                            <label for="terms" class="form-check-label text-muted small">I agree to the <a href="#" class="text-accent">Terms of Service</a> and <a href="#" class="text-accent">Privacy Policy</a></label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3">
                            Create Account <i class="bi bi-person-plus ms-2"></i>
                        </button>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small">Already part of our community? 
                            <a href="{{ route('login') }}" class="text-accent fw-bold">Sign In Here</a>
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
    .card-glass {
        border: 1px solid var(--border-light);
        background: var(--bg-surface);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
</style>
@endsection
