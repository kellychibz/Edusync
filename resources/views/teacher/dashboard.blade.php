@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold">Teacher Dashboard</h2>
                <h3 class="text-xl mt-4">Welcome, {{ $teacher->user->name }}!</h3>
                <p class="text-gray-600 mt-2">You have teacher privileges.</p>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
                    <!-- Classes Card -->
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Total Classes</p>
                                <p class="text-2xl font-semibold text-blue-900">{{ $classGroups->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects Card -->
                    <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Subjects</p>
                                <p class="text-2xl font-semibold text-green-900">{{ $subjects->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Students Card -->
                    <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Total Students</p>
                                <p class="text-2xl font-semibold text-purple-900">
                                    {{ $classGroups->sum('students_count') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Attendance Card -->
                    <div class="bg-red-50 border border-red-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600">Today's Attendance</p>
                                <p class="text-2xl font-semibold text-red-900">{{ $todaysAttendanceCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Grades This Week Card -->
                    <div class="bg-orange-50 border border-orange-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-orange-600">Grades This Week</p>
                                <p class="text-2xl font-semibold text-orange-900">
                                    {{ $recentGrades->where('created_at', '>=', now()->subWeek())->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <!-- Attendance Management Card -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">Attendance Management</h4>
                        <p class="mt-2 text-gray-600">Mark and view student attendance</p>
                        <a href="{{ route('teacher.attendance.index') }}" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">Manage Attendance</a>
                    </div>

                    <!-- Grade Management Card -->
                    {{-- Find the Grades card and update the button --}}
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Grade Management</h3>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">Manage student grades, create assignments, and track performance.</p>
                        <a href="{{ route('teacher.grades.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-tasks mr-2"></i>
                            Manage Grading
                        </a>
                    </div>

                    {{-- Class Management Card --}}
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Class Management</h3>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                            </div>
                        </div>
                        
                        @if(isset($classGroups) && $classGroups->count() > 0)
                            <p class="text-gray-600 mb-4">You are assigned to {{ $classGroups->count() }} class(es).</p>
                            
                            <div class="space-y-3 mb-4">
                                @foreach($classGroups as $class)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $class->name }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $class->students_count }} students • 
                                                {{ $class->gradeLevel->name ?? 'No grade level' }}
                                            </p>
                                        </div>
                                        <a href="{{ route('teacher.class-groups.show', $class) }}" 
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                            View
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            
                            <a href="{{ route('teacher.grades.index') }}" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Manage All Classes
                            </a>
                        @else
                            <p class="text-gray-600 mb-4">View class details and students.</p>
                            <div class="text-center py-4">
                                <i class="fas fa-chalkboard-teacher fa-2x text-gray-300 mb-2"></i>
                                <p class="text-gray-500">No classes assigned yet.</p>
                                <p class="text-sm text-gray-400 mt-1">Contact administrator to get assigned to classes.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Assessment Management Card -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Assessment Management</h4>
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">Configure term assessments, set CA percentages, and manage final exams.</p>
                        <div class="space-y-3">
                            <a href="{{ route('teacher.assessments') }}" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Manage Assessments
                            </a>
                            
                            @if(isset($classGroups) && $classGroups->count() > 0)
                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Configure button that goes to the main assessments page -->
                                    <a href="{{ route('teacher.assessments') }}" 
                                    class="inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Configure
                                    </a>
                                    <a href="#" 
                                    class="inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Reports
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <p class="text-sm text-gray-500">No classes assigned to configure</p>
                                </div>
                            @endif
                        </div>
                    </div>



                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">

                    <!-- My Classes Section -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">My Classes</h3>
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
                                                            <span class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                                </svg>
                                                                {{ $classGroup->subjects->count() }} subjects
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('teacher.grades.index') }}"
                                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition font-medium">
                                                            Manage Grades
                                                        </a>
                                                        @if($classGroup->subjects->count() > 0)
                                                            <a href="{{ route('teacher.attendance.create', [
                                                                'class_id' => $classGroup->id,
                                                                'subject_id' => $classGroup->subjects->first()->id,
                                                                'date' => \Carbon\Carbon::today()->format('Y-m-d')
                                                            ]) }}"
                                                            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition font-medium">
                                                                Mark Attendance
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Show assigned subjects for this class -->
                                                @if($classGroup->subjects->count() > 0)
                                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                                        <p class="text-xs font-medium text-gray-500 mb-2">Your Subjects:</p>
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach($classGroup->subjects as $subject)
                                                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    {{ $subject->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
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

                    <!-- Recent Activity & Quick Stats -->
                    <div class="space-y-8">
                        <!-- Recent Grades -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Grades</h3>
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
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Today's Attendance</h3>
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
    </div>
</div>
@endsection