@extends('frontend.layouts.app')

@section('title', 'Confirm Password | Educater')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card-glass p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="text-gradient-green mb-2">Secure Area</h2>
                    <p class="text-muted small">This is a secure area of the application. Please confirm your password before continuing.</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label text-white small fw-bold">PASSWORD</label>
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

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3">
                            Confirm Password <i class="bi bi-shield-check ms-2"></i>
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
