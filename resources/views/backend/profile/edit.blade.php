@extends('backend.layouts.master')

@section('title', 'Profile Settings')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-violet-700 p-8 text-white shadow-lg">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Profile Settings</h1>
            <p class="text-indigo-100">Manage your account information and security settings.</p>
        </div>
        <div class="absolute top-0 right-0 -mr-16 -mt-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    @if (session('status') && str_contains(session('status'), 'profile-updated'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Success!</span> Profile updated successfully.
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Profile Information</h3>
                </div>
                <div class="p-8">
                    <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="flex items-center gap-6 mb-8">
                            <div class="relative group">
                                <div id="image-preview-container" class="w-32 h-32 rounded-3xl bg-indigo-50 flex items-center justify-center overflow-hidden border-2 border-dashed border-indigo-200 group-hover:border-indigo-500 transition-all cursor-pointer relative">
                                    @if($user->profile_photo_path)
                                        <img id="image-preview" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span id="image-placeholder" class="text-4xl font-bold text-indigo-300">{{ substr($user->name, 0, 1) }}</span>
                                        <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="bi bi-camera-fill text-white text-2xl"></i>
                                    </div>
                                </div>
                                <input type="file" name="profile_image" id="profile_image_input" class="hidden" accept="image/*">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-1">Your Profile Picture</h4>
                                <p class="text-sm text-gray-500 mb-3">Click on the image to upload a new one. PNG, JPG or GIF (max. 1MB)</p>
                                <button type="button" onclick="document.getElementById('profile_image_input').click()" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-xl transition-colors">
                                    Change Photo
                                </button>
                                @error('profile_image')
                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <script>
                            document.getElementById('image-preview-container').onclick = function() {
                                document.getElementById('profile_image_input').click();
                            };

                            document.getElementById('profile_image_input').onchange = function(evt) {
                                const [file] = this.files;
                                if (file) {
                                    const preview = document.getElementById('image-preview');
                                    const placeholder = document.getElementById('image-placeholder');
                                    preview.src = URL.createObjectURL(file);
                                    preview.classList.remove('hidden');
                                    if (placeholder) placeholder.classList.add('hidden');
                                }
                            }
                        </script>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" required>
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" required>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Update -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Security</h3>
                </div>
                <div class="p-8">
                    @if (session('status') === 'password-updated')
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                            Password updated successfully.
                        </div>
                    @endif
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-bold text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                @error('current_password', 'updatePassword')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                    @error('password', 'updatePassword')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Account Actions -->
        <div class="space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 text-center">
                    <div class="w-24 h-24 mx-auto rounded-full bg-indigo-500 flex items-center justify-center text-3xl font-bold text-white mb-4 ring-8 ring-indigo-50">
                         @if($user->profile_photo_path)
                            <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full">
                        @else
                            {{ substr($user->name, 0, 1) }}
                        @endif
                    </div>
                    <h4 class="text-xl font-bold text-gray-900">{{ $user->name }}</h4>
                    <p class="text-gray-500 text-sm mb-6">{{ $user->email }}</p>
                    <div class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase bg-indigo-100 text-indigo-600">
                        {{ auth()->user()->getRoleNames()[0] ?? 'Admin' }}
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-3xl border border-red-100 overflow-hidden p-8">
                <h3 class="text-lg font-bold text-red-900 mb-2">Danger Zone</h3>
                <p class="text-sm text-red-600 mb-6">Once you delete your account, there is no going back. Please be certain.</p>
                <button type="button" onclick="confirmDelete()" class="w-full py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">Delete Account</button>
            </div>
        </div>
    </div>
</div>

<!-- Simple Delete Confirmation (Using a hidden form) -->
<form id="delete-profile-form" method="POST" action="{{ route('admin.profile.destroy') }}" class="hidden">
    @csrf
    @method('delete')
    <input type="password" name="password" id="confirm_password_field">
</form>

<script>
    function confirmDelete() {
        Swal.fire({
            title: '<h3 class="text-2xl font-bold text-red-600">Delete Account?</h3>',
            html: `
                <div class="text-left space-y-4">
                    <p class="text-gray-600">This action is <b>permanent</b> and cannot be undone. All your data will be removed.</p>
                    <p class="text-sm font-medium text-gray-700">Please enter your password to confirm:</p>
                </div>
            `,
            input: 'password',
            inputPlaceholder: 'Enter your password',
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off',
                class: 'w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-red-500 outline-none mt-2'
            },
            showCancelButton: true,
            confirmButtonText: 'Delete Permanently',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl border-0 shadow-2xl',
                confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all ml-3',
                cancelButton: 'px-8 py-3 rounded-xl font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all'
            },
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('Password is required to delete your account');
                    return false;
                }
                return password;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('confirm_password_field').value = result.value;
                document.getElementById('delete-profile-form').submit();
            }
        });
    }
</script>
@endsection
