@extends('frontend.layouts.app')

@section('title', 'Reset Password | Educater')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card-glass p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="text-gradient-green mb-2">New Password</h2>
                    <p class="text-muted small">Choose a secure password for your account</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label text-white small fw-bold">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" placeholder="name@example.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1 small text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label text-white small fw-bold">NEW PASSWORD</label>
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
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label text-white small fw-bold">CONFIRM PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                                class="form-control border-start-0 ps-0" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3">
                            Reset Password <i class="bi bi-check-circle ms-2"></i>
                        </button>
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
