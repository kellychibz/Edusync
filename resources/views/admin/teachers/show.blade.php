@extends('layouts.app')

@section('content')
<div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <!-- Profile Avatar -->
                <div class="flex-shrink-0">
                    @if($teacher->profile_image)
                        <img src="{{ $teacher->profile_image_url }}" alt="{{ $teacher->user->name }}"
                            class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full border-4 border-white shadow-lg">
                            <span class="text-2xl font-bold text-white">{{ substr($teacher->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $teacher->user->name }}</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Teacher
                        </span>
                        @if($teacher->primaryDepartment())
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                            {{ $teacher->primaryDepartment()->name }}
                        </span>
                        @endif
                        <span class="text-sm text-gray-500">
                            {{ $teacher->years_of_experience }} years experience
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                </a>
                <a href="{{ route('admin.teachers.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-4">
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $teacher->classGroups->sum('students_count') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Subjects</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $teacher->subjects->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-purple-50 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Classes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $teacher->classGroups->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-orange-50 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Workload</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $workloadSummary['total_periods'] }}/week</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-8">
                <!-- Departments Section -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Department Assignments</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $teacher->departments->count() }} Departments
                        </span>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($teacher->departments as $department)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $department->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $department->description }}</p>
                                        </div>
                                    </div>
                                    @if($department->pivot->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Primary
                                    </span>
                                    @endif
                                </div>
                                @endforeach
                                
                                @if($teacher->departments->count() === 0)
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No departments assigned.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teaching Subjects -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Teaching Subjects</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $teacher->subjects->count() }} Subjects
                        </span>
                    </div>
                    <div class="p-6">
                        @if($teacher->subjects->count() > 0)
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach($teacher->subjects as $subject)
                            <div class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $subject->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $subject->department->name }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No subjects assigned yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Workload Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Teaching Workload</h3>
                        <a href="{{ route('admin.teachers.workload', $teacher) }}" 
                           class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            View Details
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Periods/Week</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $workloadSummary['total_periods'] }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-medium text-gray-600">Classes</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $workloadSummary['total_classes'] }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center p-4 bg-purple-50 rounded-lg">
                                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-medium text-gray-600">Subjects</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $workloadSummary['total_subjects'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Class Subject Assignments -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Class Subject Assignments</h3>
                        <a href="{{ route('admin.teachers.manage-assignments', $teacher) }}" 
                        class="px-3 py-1 text-sm text-green-600 bg-green-100 rounded-md hover:bg-green-200">
                            Manage Assignments
                        </a>
                    </div>
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200">
                            @forelse($teacher->classSubjectAssignments as $assignment)
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $assignment->classGroup->name ?? 'Class Not Found' }} - {{ $assignment->subject->name ?? 'Subject Not Found' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $assignment->periods_per_week ?? 0 }} periods/week
                                        </p>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="px-4 py-4 sm:px-6">
                                <p class="text-sm text-gray-500">No class-subject assignments.</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection