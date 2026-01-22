{{-- resources/views/teacher/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                Welcome, {{ $teacher->user->name }}
            </h1>
            <p class="text-gray-600">
                @if($teacher->department)
                    {{ $teacher->department->name }} Department
                @else
                    Teacher Dashboard
                @endif
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <!-- Total Classes -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Classes</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $classGroups->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Students</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $classGroups->sum('students_count') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Subjects Taught -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Subjects</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $subjects->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Grades -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Grades This Week</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $recentGrades->where('created_at', '>=', now()->subWeek())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- NEW: Today's Attendance -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Attendance</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $todaysAttendanceCount ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- My Classes Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">My Classes</h2>
                        <p class="text-sm text-gray-600 mt-1">Classes you're currently teaching</p>
                    </div>
                    <div class="p-6">
                        @if($classGroups->count() > 0)
                            <div class="space-y-4">
                                @foreach($classGroups as $classGroup)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h3 class="font-medium text-gray-900">{{ $classGroup->name }}</h3>
                                                    <div class="flex items-center mt-1 space-x-4 text-sm text-gray-600">
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                                            </svg>
                                                            {{ $classGroup->students_count }} students
                                                        </span>
                                                        @if($classGroup->gradeLevel)
                                                            <span class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $classGroup->gradeLevel->name }}
                                                            </span>
                                                        @endif
                                                        @if($classGroup->stream_type)
                                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                                {{ ucfirst($classGroup->stream_type) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('teacher.class-groups.show', $classGroup) }}"
                                                       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                                        View Class
                                                    </a>
                                                    @php
                                                        $firstSubject = $classGroup->subjects->first();
                                                    @endphp
                                                    @if($firstSubject)
                                                        <a href="{{ route('teacher.attendance.create', [
                                                            'class_id' => $classGroup->id,
                                                            'subject_id' => $firstSubject->id,
                                                            'date' => \Carbon\Carbon::today()->format('Y-m-d')
                                                        ]) }}"
                                                           class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors font-medium">
                                                            Mark Attendance
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <p class="mt-4 text-gray-500">No classes assigned yet.</p>
                                <p class="text-sm text-gray-400">Contact administration to get assigned to classes.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Subjects Section -->
                <div class="bg-white rounded-lg shadow mt-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">My Subjects</h2>
                        <p class="text-sm text-gray-600 mt-1">Subjects you're currently teaching</p>
                    </div>
                    <div class="p-6">
                        @if($subjects->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($subjects as $subject)
                                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <h3 class="font-medium text-gray-900">{{ $subject->name }}</h3>
                                        <div class="flex items-center mt-2 text-sm text-gray-600">
                                            @if($subject->department)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                                    {{ $subject->department->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('teacher.attendance.index') }}?subject_id={{ $subject->id }}"
                                               class="text-sm text-red-600 hover:text-red-500 font-medium">
                                                View Attendance →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No subjects assigned yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - Quick Actions & Recent Activity -->
            <div class="space-y-8">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <!-- Attendance Quick Action -->
                            <a href="{{ route('teacher.attendance.index') }}" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-200 transition-colors group">
                                <div class="p-2 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span class="font-medium text-gray-900">Manage Attendance</span>
                                    <p class="text-sm text-gray-600">Mark and view student attendance</p>
                                </div>
                            </a>

                            <a href="{{ route('teacher.grades.create') }}" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                                <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span class="font-medium text-gray-900">Enter Grades</span>
                                    <p class="text-sm text-gray-600">Add new grades for students</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('teacher.grades.index') }}" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition-colors group">
                                <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span class="font-medium text-gray-900">View All Grades</span>
                                    <p class="text-sm text-gray-600">Browse and manage all grades</p>
                                </div>
                            </a>

                            @if($classGroups->count() > 0)
                                <a href="{{ route('teacher.class-groups.show', $classGroups->first()) }}" 
                                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition-colors group">
                                    <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <span class="font-medium text-gray-900">View Students</span>
                                        <p class="text-sm text-gray-600">See student lists and profiles</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Grades -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Grades</h2>
                        <p class="text-sm text-gray-600 mt-1">Latest grade entries</p>
                    </div>
                    <div class="p-6">
                        @if($recentGrades->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentGrades as $grade)
                                    <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $grade->student->user->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $grade->subject->name }}</p>
                                                </div>
                                                <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                    @if($grade->score >= 80) bg-green-100 text-green-800
                                                    @elseif($grade->score >= 70) bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $grade->score }}%
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $grade->assignment_type }} • {{ $grade->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('teacher.grades.index') }}" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                                    View All Grades →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="mt-4 text-gray-500">No grades entered yet.</p>
                                <a href="{{ route('teacher.grades.create') }}" class="inline-block mt-2 text-sm text-blue-600 hover:text-blue-500 font-medium">
                                    Enter your first grade →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Attendance Quick Stats -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Attendance Today</h2>
                        <p class="text-sm text-gray-600 mt-1">Quick overview</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="text-sm font-medium text-green-800">Present</span>
                                <span class="text-lg font-semibold text-green-900">{{ $todaysPresentCount ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                                <span class="text-sm font-medium text-red-800">Absent</span>
                                <span class="text-lg font-semibold text-red-900">{{ $todaysAbsentCount ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <span class="text-sm font-medium text-yellow-800">Late</span>
                                <span class="text-lg font-semibold text-yellow-900">{{ $todaysLateCount ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('teacher.attendance.reports') }}" class="text-sm text-red-600 hover:text-red-500 font-medium">
                                View Detailed Reports →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection