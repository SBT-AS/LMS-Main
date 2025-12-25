@extends('frontend.layouts.app')

@section('title', 'Forgot Password | Educater')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card-glass p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="text-gradient-green mb-2">Reset Password</h2>
                    <p class="text-muted small">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success bg-accent-dim text-accent border-accent-dim mb-4 small">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label text-white small fw-bold">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-light text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" placeholder="name@example.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1 small text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3">
                            Email Reset Link <i class="bi bi-send ms-2"></i>
                        </button>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted small">Remembered your password? 
                            <a href="{{ route('login') }}" class="text-accent fw-bold">Back to Login</a>
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
