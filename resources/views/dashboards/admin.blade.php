{{-- resources/views/dashboards/admin.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold">Admin Dashboard</h2>
                <h3 class="text-xl mt-4">Welcome, {{ auth()->user()->name }}!</h3>
                <p class="text-gray-600 mt-2">You have administrator privileges.</p>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
                    <!-- Students Card -->
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Total Students</p>
                                <p class="text-2xl font-semibold text-blue-900">{{ $stats['total_students'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Teachers Card -->
                    <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Total Teachers</p>
                                <p class="text-2xl font-semibold text-green-900">{{ $stats['total_teachers'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Classes Card -->
                    <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Total Classes</p>
                                <p class="text-2xl font-semibold text-purple-900">{{ $stats['total_classes'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Departments Card -->
                    <div class="bg-orange-50 border border-orange-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-orange-600">Departments</p>
                                <p class="text-2xl font-semibold text-orange-900">{{ $stats['total_departments'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- NEW: Attendance Card -->
                    <div class="bg-red-50 border border-red-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600">Today's Attendance</p>
                                <p class="text-2xl font-semibold text-red-900">{{ $stats['todays_attendance'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <!-- Student Management Card -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">Student Management</h4>
                        <p class="mt-2 text-gray-600">Manage all students in the system</p>
                        <a href="{{ route('admin.students.index') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Manage Students</a>
                    </div>

                    <!-- Teacher Management Card -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">Teacher Management</h4>
                        <p class="mt-2 text-gray-600">Manage teaching staff and assignments</p>
                        <a href="{{ route('admin.teachers.index') }}" class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Manage Teachers</a>
                    </div>

                    <!-- Class Management Card -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">Class Management</h4>
                        <p class="mt-2 text-gray-600">Create and manage classes</p>
                        <a href="{{ route('admin.class-groups.index') }}" class="inline-block mt-4 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">Manage Classes</a>
                    </div>

                    <!-- Curriculum Management Card -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">Curriculum Management</h4>
                        <p class="mt-2 text-gray-600">Manage streams and subject allocations</p>
                        <a href="{{ route('admin.streams.index') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Manage Curriculum</a>
                    </div>

                    <!-- NEW: Attendance Management Card -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-800">Attendance Management</h4>
                        <p class="mt-2 text-gray-600">View and manage student attendance</p>
                        <a href="{{ route('admin.attendance.index') }}" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">View Attendance</a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Grades</h3>
                    </div>
                    <div class="p-6">
                        @if($stats['recent_grades']->count() > 0)
                            <div class="space-y-3">
                                @foreach($stats['recent_grades'] as $grade)
                                    @if($grade->student && $grade->student->user && $grade->subject && $grade->teacher && $grade->teacher->user)
                                        <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">{{ substr($grade->student->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $grade->student->user->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $grade->subject->name }} • {{ $grade->teacher->user->name }}</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                @if($grade->grade >= 80) bg-green-100 text-green-800
                                                @elseif($grade->grade >= 70) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $grade->grade }}%
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No grades recorded yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection