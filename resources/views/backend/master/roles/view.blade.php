@extends('backend.layouts.master')

@section('title', 'View Role')
@section('header_title', 'View Role Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        
        {{-- Card Header --}}
        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="bi bi-shield-lock text-indigo-600"></i>
                Role Information
            </h2>
            <a href="{{ route('admin.roles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-all duration-200 font-medium shadow-sm">
                <i class="bi bi-arrow-left mr-2"></i> Back 
            </a>
        </div>

        {{-- Card Content --}}
        <div class="p-8">
            <div class="grid gap-8">
                
                {{-- Role Name Section --}}
                <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100/50">
                    <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Role Name
                    </label>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $role->name }}</span>
                    </div>
                </div>

                {{-- Permissions Section --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-key text-gray-400"></i>
                        Assigned Permissions
                    </h3>
                    
                    @if($role->permissions->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($role->permissions as $permission)
                                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-indigo-300 hover:shadow-md transition-all duration-200 group">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mr-3 group-hover:scale-125 transition-transform"></div>
                                    <span class="text-gray-700 text-sm font-medium group-hover:text-indigo-700">{{ $permission->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <i class="bi bi-exclamation-circle text-gray-400 text-2xl mb-2 block"></i>
                            <p class="text-gray-500">No permissions assigned to this role.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
