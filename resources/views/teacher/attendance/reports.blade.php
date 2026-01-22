{{-- resources/views/teacher/attendance/reports.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Attendance Reports</h2>
                        <p class="text-gray-600 mt-1">View attendance analytics and statistics</p>
                    </div>
                    <form method="GET" class="flex items-center space-x-2">
                        <input type="month" name="month" value="{{ $month }}" 
                               class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                        </button>
                    </form>
                </div>

                <!-- Summary Statistics -->
                @php
                    $totalRecords = 0;
                    $presentRecords = 0;
                    
                    foreach($attendanceSummary as $class => $subjects) {
                        foreach($subjects as $subject => $types) {
                            foreach($types as $type) {
                                $totalRecords += $type->count;
                                if($type->attendanceType->is_present) {
                                    $presentRecords += $type->count;
                                }
                            }
                        }
                    }
                    
                    $attendanceRate = $totalRecords > 0 ? round(($presentRecords / $totalRecords) * 100, 2) : 0;
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Total Records -->
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Total Records</p>
                                <p class="text-2xl font-semibold text-blue-900">{{ $totalRecords }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Present Records -->
                    <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Present Records</p>
                                <p class="text-2xl font-semibold text-green-900">{{ $presentRecords }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Absent Records -->
                    <div class="bg-red-50 border border-red-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600">Absent Records</p>
                                <p class="text-2xl font-semibold text-red-900">{{ $totalRecords - $presentRecords }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Rate -->
                    <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Attendance Rate</p>
                                <p class="text-2xl font-semibold text-purple-900">{{ $attendanceRate }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Detailed Attendance by Class and Subject</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
                    </div>
                    <div class="p-6">
                        @if($attendanceSummary->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Excused</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sick</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($attendanceSummary as $className => $subjects)
                                            @foreach($subjects as $subjectName => $types)
                                                @php
                                                    $typeCounts = [];
                                                    $total = 0;
                                                    foreach($types as $type) {
                                                        $typeCounts[$type->attendanceType->name] = $type->count;
                                                        $total += $type->count;
                                                    }
                                                    
                                                    $present = ($typeCounts['Present'] ?? 0) + ($typeCounts['Late'] ?? 0);
                                                    $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;
                                                @endphp
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $className }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $subjectName }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                                        {{ $typeCounts['Present'] ?? 0 }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                                        {{ $typeCounts['Absent'] ?? 0 }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 font-medium">
                                                        {{ $typeCounts['Late'] ?? 0 }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">
                                                        {{ $typeCounts['Excused Absence'] ?? 0 }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 font-medium">
                                                        {{ $typeCounts['Sick'] ?? 0 }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $total }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rate >= 90 ? 'bg-green-100 text-green-800' : ($rate >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                            {{ $rate }}%
                                                        </span>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance data</h3>
                                <p class="mt-1 text-sm text-gray-500">No attendance records found for the selected period.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection