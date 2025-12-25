@extends('frontend.layouts.app')

@section('title', 'Register | Educater')

@section('content')
<div class="container-fluid position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center py-5">
    <!-- Background Glows -->
    <div class="position-absolute top-50 start-0 translate-middle rounded-circle"
         style="width: 600px; height: 600px; background: radial-gradient(circle, rgba(0, 220, 130, 0.1) 0%, transparent 70%); filter: blur(100px); pointer-events: none;"></div>
    <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle"
         style="width: 600px; height: 600px; background: radial-gradient(circle, rgba(102, 16, 242, 0.1) 0%, transparent 70%); filter: blur(100px); pointer-events: none;"></div>

    <div class="row justify-content-center w-100 position-relative z-1">
        <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-5">
            <div class="card-glass border-0 rounded-4 overflow-hidden shadow-2xl">
                <!-- Top Accent Bar -->
                <div style="height: 4px; background: linear-gradient(90deg, #6610f2, var(--accent-color));"></div>
                
                <div class="p-4 p-md-5">
                    <div class="text-center mb-5">
                        <div class="mb-3 d-inline-block">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-bold tracking-wider">NEW ACCOUNT</span>
                        </div>
                        <h2 class="fw-bold text-white mb-2">Join the Future</h2>
                        <p class="text-muted">Create your account to start learning from industry experts</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label text-white-50 small fw-bold mb-2">FULL NAME</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 border-light text-white-50 ps-3">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                                        class="form-control border-start-0 ps-2 @error('name') is-invalid @enderror" placeholder="John Doe">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block mt-2 small text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label text-white-50 small fw-bold mb-2">EMAIL ADDRESS</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 border-light text-white-50 ps-3">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                                        class="form-control border-start-0 ps-2 @error('email') is-invalid @enderror" placeholder="name@example.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-2 small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label text-white-50 small fw-bold mb-2">PASSWORD</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 border-light text-white-50 ps-3">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" name="password" required autocomplete="new-password" 
                                        class="form-control border-start-0 ps-2 @error('password') is-invalid @enderror" placeholder="••••••••">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-2 small text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label text-white-50 small fw-bold mb-2">CONFIRM PASS</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 border-light text-white-50 ps-3">
                                        <i class="bi bi-shield-lock"></i>
                                    </span>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                                        class="form-control border-start-0 ps-2" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-3 rounded-3 fw-bold shadow-lg transform-hover">
                                Create Account <i class="bi bi-person-plus ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center mt-5">
                            <p class="text-white-50 small">Already part of our community? 
                                <a href="{{ route('login') }}" class="text-accent fw-bold text-decoration-none hover-underline">Sign In Here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-glass {
        background: rgba(13, 19, 33, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }
    .input-group-text {
        border-color: rgba(255, 255, 255, 0.1) !important;
        background: rgba(255, 255, 255, 0.02) !important;
    }
    .form-control {
        background: rgba(255, 255, 255, 0.02) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .form-control:focus {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 20px rgba(0, 220, 130, 0.1);
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.2) !important;
    }
    .transform-hover {
        transition: all 0.3s ease;
    }
    .transform-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 220, 130, 0.2) !important;
    }
    .hover-underline:hover {
        text-decoration: underline !important;
    }
    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
    }
    .tracking-wider {
        letter-spacing: 0.05em;
    }
</style>
@endsection
