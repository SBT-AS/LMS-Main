@extends('backend.layouts.master')

@section('title', 'Manage Permissions')
@section('header_title', 'Role Management')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Manage Permissions for <span class="text-indigo-600">{{ $role->name }}</span></h2>
            <a href="{{ route('admin.roles.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                <i class="bi bi-arrow-left mr-1"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.roles.permissions.store', $role->id) }}" method="POST" id="crudForm" class="p-6">
            @csrf
            
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-700">Available Permissions</h3>
                    <div class="flex items-center">
                        <input type="checkbox" id="checkAll" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="checkAll" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Select All</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($permissions as $permission)
                    <div class="flex items-start p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center h-5">
                            <input id="perm_{{ $permission->id }}" 
                                   name="permissions[]" 
                                   value="{{ $permission->name }}" 
                                   type="checkbox" 
                                   class="permission-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                   {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="perm_{{ $permission->id }}" class="font-medium text-gray-700 cursor-pointer select-none">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <button type="submit" id="saveBtn" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors shadow-md shadow-indigo-200">
                    <i class="bi bi-save mr-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        checkAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Update "Select All" state if all individual checkboxes are changed
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    checkAll.checked = false;
                } else {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    checkAll.checked = allChecked;
                }
            });
        });
        
        // Initial check for "Select All"
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        if(checkboxes.length > 0) {
            checkAll.checked = allChecked;
        }
    });
</script>
@endpush
