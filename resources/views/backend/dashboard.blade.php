@extends('backend.layouts.master')

@section('title', 'Dashboard')

@section('header_title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Students -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                    <i class="bi bi-people-fill text-2xl"></i>
                </div>
                <span class="flex items-center text-xs font-semibold text-green-500 bg-green-50 px-2.5 py-0.5 rounded-full">
                    <i class="bi bi-arrow-up-short mr-1"></i> 12%
                </span>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Total Students</h3>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-bold text-gray-900">{{ number_format($total_students) }}</h4>
                </div>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                    <i class="bi bi-book-half text-2xl"></i>
                </div>
                <span class="flex items-center text-xs font-semibold text-indigo-500 bg-indigo-50 px-2.5 py-0.5 rounded-full">
                    {{ $active_courses }} Active
                </span>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Total Courses</h3>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-bold text-gray-900">{{ number_format($total_courses) }}</h4>
                </div>
            </div>
        </div>

        <!-- Total Enrollments -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                    <i class="bi bi-mortarboard-fill text-2xl"></i>
                </div>
                <span class="flex items-center text-xs font-semibold text-emerald-500 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                    Lifetime
                </span>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Enrollments</h3>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-bold text-gray-900">{{ number_format($total_enrollments) }}</h4>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                    <i class="bi bi-currency-dollar text-2xl"></i>
                </div>
                <span class="flex items-center text-xs font-semibold text-amber-500 bg-amber-50 px-2.5 py-0.5 rounded-full">
                    Total
                </span>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Total Revenue</h3>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-bold text-gray-900">${{ number_format($total_revenue, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Enrollments -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Recent Enrollments</h3>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recent_enrollments as $enrollment)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3">
                                            {{ substr($enrollment->student_name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $enrollment->student_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $enrollment->course_title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($enrollment->created_at)->diffForHumans() }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="viewStudentDetail('{{ $enrollment->student_name }}', '{{ $enrollment->student_name }}@gmail.com')" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 italic">No recent enrollments found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Courses -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">New Courses</h3>
                    <a href="{{ route('admin.courses.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Manage Courses</a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($recent_courses as $course)
                        <div class="flex items-center p-3 rounded-xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all">
                            <div class="h-12 w-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="Course" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-600">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-grow min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $course->title }}</h4>
                                <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                    <span class="font-medium text-indigo-600">${{ number_format($course->price, 2) }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $course->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <button onclick="viewStudentDetail('Top Student', 'top@student.com')" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                <i class="bi bi-person-lines-fill"></i>
                            </button>
                        </div>
                        
                        @empty
                        <div class="col-span-2 text-center py-4 text-sm text-gray-500 italic">No courses created yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Activity/Quick Links -->
        <div class="space-y-6">
            <!-- Recent Students -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">New Students</h3>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($recent_students as $student)
                    <div class="flex items-center group">
                        <div class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold mr-3 group-hover:bg-emerald-200 transition-colors">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $student->name }}</h4>
                            <p class="text-xs text-gray-500 truncate">{{ $student->email }}</p>
                        </div>
                        <div class="text-[10px] text-gray-400 font-medium whitespace-nowrap ml-2">
                            {{ $student->created_at->diffForHumans(null, true) }}
                        </div>
                        <button onclick="viewStudentDetail('{{ $student->name }}', '{{ $student->email }}')" class="ml-3 p-1 text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-2 italic">No new students.</p>
                    @endforelse
                </div>
                <div class="p-4 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}" class="block text-center text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">
                        View All Users
                    </a>
                </div>
                
            </div>

            <!-- Quick Stats/Info -->
            <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold mb-2">LMS System Status</h3>
                    <p class="text-indigo-100 text-sm mb-4">You have {{$total_courses}} total courses and {{$total_students}} enrolled students. Keep up the good work!</p>
                    <div class="space-y-3">
                        <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                            <div class="text-[10px] uppercase tracking-wider font-bold text-indigo-200 mb-1">Server Time</div>
                            <div class="text-sm font-mono">{{ now()->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<!-- Student Detail Modal -->
<div id="studentDetailModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeStudentModal()"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 z-10">
            
            <h3 class="text-xl font-bold mb-4">Student Details</h3>

            <!-- BASIC INFO -->
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Name</span>
                    <span class="font-semibold" id="studentName">Arman Khan</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email</span>
                    <span class="font-semibold" id="studentEmail">arman@example.com</span>
                </div>
            </div>

            <!-- FEATURE ACCESS -->
            <div class="mt-6">
                <h4 class="text-md font-semibold mb-3 text-gray-800">
                    Feature Access
                </h4>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Live Lecture</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">
                            Allowed
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Recorded Videos</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">
                            Allowed
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Chat with Teacher</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">
                            Not Allowed
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Download Notes</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">
                            Allowed
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Certificate</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-700">
                            After Completion
                        </span>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="mt-6 flex gap-2">
                <button class="flex-1 bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700">
                    Start Live Lecture
                </button>
                <button onclick="closeStudentModal()" class="flex-1 bg-gray-200 py-2 rounded-xl">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function viewStudentDetail(name, email) {
        document.getElementById('studentName').innerText = name;
        document.getElementById('studentEmail').innerText = email;
        document.getElementById('studentDetailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeStudentModal() {
        document.getElementById('studentDetailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

@endpush

