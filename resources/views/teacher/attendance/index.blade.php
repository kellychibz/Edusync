{{-- resources/views/teacher/attendance/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold">Attendance Management</h2>
                <h3 class="text-xl mt-4">Teacher Dashboard</h3>
                <p class="text-gray-600 mt-2">Manage attendance for your assigned classes.</p>

                <!-- Today's Summary -->
                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Today's Summary - {{ $today }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Present Card -->
                        <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-green-600">Present Today</p>
                                    <p class="text-2xl font-semibold text-green-900">{{ $todayAttendance['Present']->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Absent Card -->
                        <div class="bg-red-50 border border-red-200 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-red-600">Absent Today</p>
                                    <p class="text-2xl font-semibold text-red-900">{{ $todayAttendance['Absent']->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Late Card -->
                        <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-yellow-600">Late Today</p>
                                    <p class="text-2xl font-semibold text-yellow-900">{{ $todayAttendance['Late']->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Classes -->
                <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Your Assigned Classes</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage attendance for each class and subject</p>
                    </div>
                    <div class="p-6">
                        @if($assignedClasses->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Today's Attendance</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($assignedClasses as $className => $subjects)
                                            @foreach($subjects as $classSubject)
                                                @php
                                                    $studentCount = $classSubject->classGroup->students->count();
                                                    $todayAttCount = $classSubject->attendanceRecords()
                                                        ->where('attendance_date', $today)
                                                        ->count();
                                                @endphp
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $className }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $classSubject->subject->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $studentCount }} students
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $todayAttCount > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                            {{ $todayAttCount }}/{{ $studentCount }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <div class="flex space-x-2">
                                                            <a href="{{ route('teacher.attendance.create', [
                                                                'class_id' => $classSubject->class_id,
                                                                'subject_id' => $classSubject->subject_id,
                                                                'date' => $today
                                                            ]) }}" 
                                                               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                Mark Attendance
                                                            </a>
                                                            <a href="{{ route('teacher.attendance.reports') }}?class_id={{ $classSubject->class_id }}&subject_id={{ $classSubject->subject_id }}" 
                                                               class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                                </svg>
                                                                Reports
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No classes assigned</h3>
                                <p class="mt-1 text-sm text-gray-500">You haven't been assigned to any classes yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection