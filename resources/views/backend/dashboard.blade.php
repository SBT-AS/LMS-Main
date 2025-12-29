@extends('backend.layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">
    <!-- Welcome Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-violet-700 p-8 text-white shadow-lg">
        <div class="relative z-10 flex items-center gap-6">
            <div class="h-20 w-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 p-1 flex-shrink-0">
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover rounded-xl">
                @else
                    <div class="h-full w-full flex items-center justify-center text-2xl font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome Back, {{ auth()->user()->name }}! 👋</h1>
                @if(auth()->user()->hasRole('student'))
                    <p class="text-indigo-100 max-w-xl">You are currently enrolled in <span class="font-bold text-white">{{ $my_enrolled_courses_count }}</span> courses and have completed <span class="font-bold text-white">{{ $my_completed_courses_count }}</span>. Keep up the great work!</p>
                @else
                    <p class="text-indigo-100 max-w-xl">Your learning ecosystem is thriving. You have <span class="font-bold text-white">{{ $total_students }}</span> students enrolled across <span class="font-bold text-white">{{ $total_courses }}</span> active courses.</p>
                @endif
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 h-32 w-32 rounded-full bg-indigo-400/20 blur-2xl"></div>
        
        <div class="absolute right-12 top-1/2 -translate-y-1/2 hidden lg:block">
            <div class="flex gap-4">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                    <div class="text-[10px] uppercase tracking-wider text-indigo-200 font-bold mb-1">Total Revenue</div>
                    <div class="text-2xl font-bold">₹{{ number_format($total_revenue, 2) }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                    <div class="text-[10px] uppercase tracking-wider text-indigo-200 font-bold mb-1">Global Reach</div>
                    <div class="text-2xl font-bold">12+ Countries</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Students -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                    <i class="bi bi-people-fill text-2xl"></i>
                </div>
                <div class="flex items-center text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-lg">
                    <i class="bi bi-graph-up-arrow mr-1"></i> Active
                </div>
            </div>
            @if(auth()->user()->hasRole('student'))
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Enrolled Courses</h3>
                <div class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($my_enrolled_courses_count) }}</div>
            @else
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Students</h3>
                <div class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($total_students) }}</div>
            @endif
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                    <i class="bi bi-journals text-2xl"></i>
                </div>
                <div class="flex items-center text-xs font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-lg">
                    {{ auth()->user()->hasRole('student') ? 'Learning' : $active_courses . ' Active' }}
                </div>
            </div>
            @if(auth()->user()->hasRole('student'))
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Recent Course</h3>
                <div class="text-xl font-bold text-gray-900 mt-1 truncate">{{ $my_recent_courses->first()->title ?? 'None' }}</div>
            @else
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Courses</h3>
                <div class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($total_courses) }}</div>
            @endif
        </div>

        <!-- Enrollments -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
                    <i class="bi bi-patch-check-fill text-2xl"></i>
                </div>
                <div class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">
                    Achievement
                </div>
            </div>
            @if(auth()->user()->hasRole('student'))
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Certificates</h3>
                <div class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($my_certificates_count) }}</div>
            @else
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Enrollments</h3>
                <div class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($total_enrollments) }}</div>
            @endif
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                    <i class="bi bi-wallet2 text-2xl"></i>
                </div>
                <div class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-lg">
                    {{ auth()->user()->hasRole('student') ? 'Progress' : 'Withdrawal' }}
                </div>
            </div>
            @if(auth()->user()->hasRole('student'))
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Completed Courses</h3>
                <div class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($my_completed_courses_count) }}</div>
            @else
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Earnings</h3>
                <div class="text-3xl font-bold text-gray-900 mt-1">₹{{ number_format($total_revenue, 2) }}</div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Activity -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Enrollments -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Recent Enrollments</h3>
                        <p class="text-sm text-gray-500">Overview of the latest student signups</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-xl transition-colors">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Student</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Course</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recent_enrollments as $enrollment)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center">
                                        
                                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-sm group-hover:scale-110 transition-transform overflow-hidden">
                                        @if($enrollment->student_image)
                                            <img src="{{ Storage::url($enrollment->student_image) }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($enrollment->student_name, 0, 1) }}
                                        @endif
                                    </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $enrollment->student_name }}</div>
                                            <div class="text-xs text-gray-400">Student ID #{{ rand(1000, 9999) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-sm text-gray-600 font-medium">{{ \Illuminate\Support\Str::limit($enrollment->course_title, 30) }}</td>
                                <td class="px-8 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($enrollment->created_at)->diffForHumans() }}</td>
                                <td class="px-8 py-4 text-right">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-600">Enrolled</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-gray-400 italic">No recent enrollments found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Courses -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Latest Course Releases</h3>
                    <p class="text-sm text-gray-500">Manage and track your newest educational content</p>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($recent_courses as $course)
                    <div class="group flex items-center p-4 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/20 transition-all duration-300">
                        <div class="h-16 w-16 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 shadow-sm">
                            @if($course->image)
                                <img src="{{ asset('storage/courses/' . $course->image) }}" alt="Course" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-600">
                                    <i class="bi bi-journal-code text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-5 flex-grow min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate mb-1">{{ $course->title }}</h4>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-extrabold text-indigo-600">${{ number_format($course->price, 2) }}</span>
                                <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                                <span class="text-xs text-gray-400 font-medium">{{ $course->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="p-2 text-gray-300 hover:text-indigo-600 transition-colors">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-8 text-gray-400 italic">No courses created yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Stats & New Users -->
        <div class="space-y-8">
            <!-- New Students Sidebar -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">New Students</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recent_students as $student)
                    <div class="px-6 py-4 flex items-center hover:bg-gray-50/50 transition-colors group">
                        <div class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold ring-4 ring-emerald-50 group-hover:scale-110 transition-transform overflow-hidden">
                            @if($student->profile_photo_path)
                                <img src="{{ Storage::url($student->profile_photo_path) }}" alt="" class="h-full w-full object-cover">
                            @else
                                {{ substr($student->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="ml-3 flex-grow min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $student->name }}</h4>
                            <p class="text-[10px] text-gray-400 uppercase font-medium tracking-wider">{{ $student->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.users.edit', $student->id) }}" class="p-2 text-gray-300 hover:text-indigo-600">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 italic">No new students today.</div>
                    @endforelse
                </div>
                <div class="p-4 bg-gray-50/50 text-center">
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-gray-500 hover:text-indigo-600 transition-colors uppercase tracking-widest">Explore Directory</a>
                </div>
            </div>

           
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Unnecessary JS removed as per request --}}
@endpush
