@extends('backend.layouts.master')

@section('title', 'View User')
@section('header_title', 'View User Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        
        {{-- Card Header --}}
        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="bi bi-person-badge text-indigo-600"></i>
                User Information
            </h2>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-all duration-200 font-medium shadow-sm">
                <i class="bi bi-arrow-left mr-2"></i> Back 
            </a>
        </div>

        {{-- Card Content --}}
        <div class="p-8">
            <div class="grid gap-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name Section --}}
                    <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100/50">
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Name
                        </label>
                        <div class="flex items-center">
                            <span class="text-lg font-bold text-gray-900">{{ $user->name }}</span>
                        </div>
                    </div>

                    {{-- Email Section --}}
                    <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100/50">
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Email
                        </label>
                        <div class="flex items-center">
                            <span class="text-lg font-medium text-gray-900">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
