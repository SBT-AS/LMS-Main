@extends('backend.layouts.master')

@section('title', 'Quiz Results')
@section('header_title', 'Quiz Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Results: {{ $quiz->title }}</h1>
            <p class="text-gray-500 text-sm mt-1">View all quiz attempts and scores</p>
        </div>
        <a href="{{ route('admin.courses.quizzes.index', $course) }}"
           class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Quizzes
        </a>
    </div>

    <!-- Quiz Info -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Questions</p>
                <p class="text-2xl font-bold text-gray-800">{{ $quiz->questions->count() }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Attempts</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $attempts->count() }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Average Score</p>
                <p class="text-2xl font-bold text-green-600">
                    @if($attempts->count() > 0)
                        {{ number_format($attempts->avg('score'), 1) }}/{{ $quiz->questions->count() }}
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Pass Rate</p>
                <p class="text-2xl font-bold text-purple-600">
                    @if($attempts->count() > 0)
                        {{ number_format($attempts->filter(fn($a) => $a->getPercentageScore() >= 70)->count() / $attempts->count() * 100, 1) }}%
                    @else
                        N/A
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Attempts List -->
    @if($attempts->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Attempts Yet</h3>
            <p class="text-gray-500">Students haven't attempted this quiz yet</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Student</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Score</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Percentage</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Completed At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($attempts as $attempt)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                            {{ substr($attempt->user->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $attempt->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $attempt->user->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-gray-800">
                                        {{ $attempt->score }}/{{ $attempt->total_questions }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold {{ $attempt->getPercentageScore() >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($attempt->getPercentageScore(), 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($attempt->getPercentageScore() >= 70)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            <i class="bi bi-check-circle-fill mr-1"></i> Passed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            <i class="bi bi-x-circle-fill mr-1"></i> Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $attempt->completed_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }
</style>
@endpush
@endsection
