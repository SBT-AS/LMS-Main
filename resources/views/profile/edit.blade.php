@extends('frontend.layouts.app')

@section('title', 'Profile Settings')

@push('styles')
<style>
    .dashboard-container {
        min-height: 80vh;
    }
    .theme-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        overflow: hidden;
    }
    .theme-card-header {
        background: rgba(255, 255, 255, 0.02);
        border-bottom: 1px solid var(--border-light);
        padding: 1rem 1.5rem;
    }
    .user-profile-header {
        background: linear-gradient(135deg, var(--accent-color) 0%, #0a58ca 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
    }
    .nav-link-dashboard {
        padding: 14px 25px;
        color: var(--text-body);
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        border-right: 4px solid transparent;
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    .nav-link-dashboard:hover, .nav-link-dashboard.active {
        background-color: rgba(255, 255, 255, 0.05);
        color: var(--accent-color);
        border-right-color: var(--accent-color);
    }
    .nav-link-dashboard i {
        width: 24px;
        margin-right: 12px;
        font-size: 1.2rem;
    }
    
    .bg-light-custom {
        background-color: var(--bg-page);
    }
    
    /* Input Styling for Dark Theme */
    .form-control {
        background-color: var(--bg-page);
        border: 1px solid var(--border-light);
        color: var(--text-body);
    }
    .form-control:focus {
        background-color: var(--bg-page);
        border-color: var(--accent-color);
        color: var(--text-body);
        box-shadow: 0 0 0 0.25rem rgba(var(--accent-color-rgb), 0.25);
    }
    .form-label {
        color: var(--text-body);
    }
</style>
@endpush

@section('content')
<div class="dashboard-container py-5 bg-light-custom">
    <div class="container">
        <div class="row g-4">
            <!-- Left Sidebar -->
            <div class="col-lg-3">
                <div class="theme-card shadow-sm position-sticky" style="top: 100px; z-index: 10;">
                    <div class="user-profile-header">
                        <div class="position-relative d-inline-block">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="avatar-circle mx-auto mb-3 shadow rounded-circle object-fit-cover" 
                                     style="width: 80px; height: 80px; border: 3px solid rgba(255,255,255,0.2);">
                            @else
                                <div class="avatar-circle mx-auto mb-3 bg-white text-dark fw-bold d-flex align-items-center justify-content-center shadow" 
                                     style="width: 80px; height: 80px; font-size: 2.5rem; border-radius: 50%;">
                                     {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-white rounded-circle">
                                <span class="visually-hidden">Online</span>
                            </span>
                        </div>
                        <h5 class="fw-bold mb-1 text-white">{{ Auth::user()->name }}</h5>
                        <p class="mb-0 text-white-50 small">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="p-0 py-2">
                        <div class="d-flex flex-column">
                            <a href="{{ route('student.dashboard') }}" class="nav-link-dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <a href="{{ route('student.dashboard') }}#my-courses" class="nav-link-dashboard">
                                <i class="bi bi-journal-album"></i> My Courses
                            </a>
                            <a href="{{ route('student.certificates.index') }}" class="nav-link-dashboard">
                                <i class="bi bi-award"></i> Certificates
                            </a>
                            <a href="{{ route('profile.edit') }}" class="nav-link-dashboard active">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                            <div class="border-top my-2 mx-3 border-secondary border-opacity-10"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-menu-form">
                                @csrf
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-menu-form').submit();" class="nav-link-dashboard text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div class="mb-4">
                    <h4 class="fw-bold text-white">Account Settings</h4>
                    <p class="text-muted">Manage your profile information and security settings.</p>
                </div>

                <!-- Profile Information -->
                <div class="theme-card shadow-sm mb-4">
                    <div class="theme-card-header">
                        <h5 class="mb-0 fw-bold">Profile Information</h5>
                    </div>
                    <div class="card-body p-4">
                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Profile updated successfully.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="mb-4 text-center">
                                <div class="position-relative d-inline-block group" style="cursor: pointer;" onclick="document.getElementById('profile_image').click()">
                                    <div id="image-preview-container-frontend" class="rounded-circle overflow-hidden border border-primary border-3" style="width: 120px; height: 120px;">
                                        @if(Auth::user()->profile_photo_path)
                                            <img id="image-preview-frontend" src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div id="image-placeholder-frontend" class="w-100 h-100 bg-primary text-white d-flex align-items-center justify-content-center fs-1">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                            <img id="image-preview-frontend" src="" alt="Preview" class="w-100 h-100 object-fit-cover d-none">
                                        @endif
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow">
                                        <i class="bi bi-camera-fill"></i>
                                    </div>
                                </div>
                                <input type="file" class="d-none @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" accept="image/*">
                                <p class="text-muted small mt-2">Click to change profile photo</p>
                                @error('profile_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <script>
                                document.getElementById('profile_image').onchange = function(evt) {
                                    const [file] = this.files;
                                    if (file) {
                                        const preview = document.getElementById('image-preview-frontend');
                                        const placeholder = document.getElementById('image-placeholder-frontend');
                                        preview.src = URL.createObjectURL(file);
                                        preview.classList.remove('d-none');
                                        if (placeholder) placeholder.classList.add('d-none');
                                    }
                                }
                            </script>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-800">
                                            Your email address is unverified.
                                            <button form="send-verification" class="btn btn-link p-0 align-baseline">
                                                Click here to re-send the verification email.
                                            </button>
                                        </p>
                                        @if (session('status') === 'verification-link-sent')
                                            <p class="text-success small mt-2">
                                                A new verification link has been sent to your email address.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4 rounded-pill">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="theme-card shadow-sm mb-4">
                    <div class="theme-card-header">
                        <h5 class="mb-0 fw-bold">Update Password</h5>
                    </div>
                    <div class="card-body p-4">
                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Password updated successfully.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">New Password</label>
                                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                                @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4 rounded-pill">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Delete Account -->
                 <div class="theme-card shadow-sm mt-4 border-danger">
                    <div class="theme-card-header">
                        <h5 class="mb-0 fw-bold text-danger">Delete Account</h5>
                    </div>
                    <div class="card-body p-4">
                         <p class="text-muted small">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                         <button type="button" class="btn btn-outline-danger rounded-pill px-4" onclick="confirmAccountDeletion()">
                            Delete Account
                         </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-account-form-frontend" method="POST" action="{{ route('profile.destroy') }}" class="d-none">
    @csrf
    @method('delete')
    <input type="password" name="password" id="confirm_password_field_frontend">
</form>

@push('scripts')
<script>
    function confirmAccountDeletion() {
        Swal.fire({
            title: '<h3 class="text-2xl font-bold text-white mt-3">Delete Account?</h3>',
            html: `
                <div class="text-center px-2">
                    <p class="text-white-50 mb-4">This action is <b>permanent</b> and cannot be undone. All your data will be removed. Please enter your password to confirm.</p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#dc3545',
            input: 'password',
            inputPlaceholder: 'Enter your password',
            showCancelButton: true,
            confirmButtonText: 'Delete Permanently',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            background: '#1a1d21',
            color: '#ffffff',
            customClass: {
                popup: 'rounded-5 border border-secondary border-opacity-10 shadow-lg',
                confirmButton: 'btn btn-danger px-4 py-2 rounded-pill fw-bold ms-3',
                cancelButton: 'btn btn-link text-secondary text-decoration-none px-4 py-2 fw-bold',
                input: 'form-control border-0 bg-dark bg-opacity-50 text-white text-center py-3 rounded-4 mt-2 mb-3 mx-auto w-75 border border-secondary border-opacity-20'
            },
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('Password is required');
                    return false;
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('confirm_password_field_frontend').value = result.value;
                document.getElementById('delete-account-form-frontend').submit();
            }
        });
    }

    @if($errors->userDeletion->has('password'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: '<span class="text-white">Validation Error</span>',
                text: '{{ $errors->userDeletion->first("password") }}',
                background: '#1a1d21',
                color: '#ffffff',
                confirmButtonText: 'Try Again',
                customClass: {
                    popup: 'rounded-4 border border-secondary border-opacity-10',
                    confirmButton: 'btn btn-primary px-4 py-2 rounded-pill fw-bold'
                },
                buttonsStyling: false
            }).then(() => {
                confirmAccountDeletion();
            });
        });
    @endif
</script>
@endpush

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

@endsection
