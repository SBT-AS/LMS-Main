@extends('frontend.layouts.app')

@section('title', 'Verify Email | Educater')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card-glass p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="text-gradient-green mb-2">Verify Email</h2>
                    <p class="text-muted small">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success bg-accent-dim text-accent border-accent-dim mb-4 small">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="d-flex flex-column gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3">
                                Resend Verification Email <i class="bi bi-envelope-at ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted small text-decoration-none">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
