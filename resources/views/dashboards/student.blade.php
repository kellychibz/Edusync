{{-- resources/views/dashboards/student.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold">Student Dashboard</h2>
                <h3 class="text-xl mt-4">Welcome, {{ auth()->user()->name }}!</h3>
                
                @if($classGroup)
                    <p class="text-gray-600 mt-2">{{ $classGroup->name }} • {{ $classGroup->gradeLevel->name ?? 'Grade Level' }}</p>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <!-- Overall Average -->
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Overall Average</p>
                                <p class="text-2xl font-semibold text-blue-900">{{ number_format($overallAverage, 1) }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Grades -->
                    <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Total Grades</p>
                                <p class="text-2xl font-semibold text-green-900">{{ $student->grades->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Class Info -->
                    <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Class</p>
                                <p class="text-lg font-semibold text-purple-900">{{ $classGroup->name ?? 'Not Assigned' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">My Grades</h4>
                        <p class="mt-2 text-gray-600">View your grades and academic progress</p>
                        <a href="{{ route('student.grades') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">View All Grades</a>
                    </div>

                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">Subject Performance</h4>
                        <p class="mt-2 text-gray-600">See how you're doing in each subject</p>
                        <a href="{{ route('student.grades') }}" class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">View Performance</a>
                    </div>
                </div>

                <!-- Recent Grades & Subject Averages -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                    <!-- Recent Grades -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Grades</h3>
                        </div>
                        <div class="p-6">
                            @if($recentGrades->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentGrades as $grade)
                                        <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $grade->subject->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $grade->assignment_type }} • {{ $grade->created_at->format('M d, Y') }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                @if($grade->score >= 80) bg-green-100 text-green-800
                                                @elseif($grade->score >= 70) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $grade->score }}%
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No grades recorded yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Subject Averages -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Subject Averages</h3>
                        </div>
                        <div class="p-6">
                            @if($subjectAverages->count() > 0)
                                <div class="space-y-4">
                                    @foreach($subjectAverages as $subjectAvg)
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">{{ $subjectAvg->subject->name }}</p>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="bg-blue-600 h-2 rounded-full" 
                                                         style="width: {{ min($subjectAvg->average, 100) }}%"></div>
                                                </div>
                                            </div>
                                            <span class="ml-4 text-sm font-medium text-gray-900">
                                                {{ number_format($subjectAvg->average, 1) }}%
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No subject averages available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection