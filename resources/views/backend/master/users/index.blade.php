@extends('backend.layouts.master')

@section('title', 'Users')
@section('header_title', 'User Management')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Users List</h2>
        
        @can('users.create')
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <i class="bi bi-plus-lg mr-2"></i> Add User
        </a>
        @endcan
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500" id="users-table">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3" width="5%">#</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Roles</th>
                        <th scope="col" class="px-6 py-3 text-center" width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTable Content --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    initTailwindDataTable(
        '#users-table',
        "{{ route('admin.users.index') }}",
        [
            {data: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name'},
            {data: 'email'},
            {data: 'roles'},
            {data: 'action', orderable: false, searchable: false, className: 'text-center'}
        ]
    );
});
</script>
@endpush
