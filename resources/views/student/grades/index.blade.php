@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">My Grades</h2>
                        <p class="text-gray-600">View your academic performance</p>
                    </div>
                    <div class="text-right">
                        @if($overallAverage)
                            <div class="text-lg font-semibold text-gray-800">
                                Overall Average: <span class="text-blue-600">{{ number_format($overallAverage, 1) }}%</span>
                            </div>
                            <div class="text-sm text-gray-500">{{ $totalAssignments }} assignments</div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="text-blue-800 font-semibold">Overall Average</div>
                        <div class="text-2xl font-bold text-blue-600 mt-2">
                            {{ $overallAverage ? number_format($overallAverage, 1) . '%' : 'N/A' }}
                        </div>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="text-green-800 font-semibold">Total Assignments</div>
                        <div class="text-2xl font-bold text-green-600 mt-2">{{ $totalAssignments }}</div>
                    </div>
                    
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="text-purple-800 font-semibold">Subjects</div>
                        <div class="text-2xl font-bold text-purple-600 mt-2">{{ $gradesBySubject->count() }}</div>
                    </div>
                </div>

                <!-- Recent Grades -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Recent Grades</h3>
                    @if($recentGrades->count() > 0)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Assignment Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Grade</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentGrades as $grade)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $grade->assignment_type }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $grade->subject->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-sm font-semibold {{ $grade->grade_color }}">
                                                    {{ $grade->grade }}/{{ $grade->max_grade }} ({{ $grade->letter_grade }})
                                                </span>
                                                <span class="ml-2 text-sm text-gray-500">
                                                    {{ number_format($grade->percentage, 1) }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($grade->date)->format('M d, Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No grades available</h3>
                            <p class="mt-2 text-gray-500">Your grades will appear here once they are recorded.</p>
                        </div>
                    @endif
                </div>

                <!-- Grades by Subject -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Grades by Subject</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($gradesBySubject as $subjectId => $subjectGrades)
                            @php
                                $subject = $subjectGrades->first()->subject;
                                $subjectAverage = $subjectGrades->avg('percentage');
                            @endphp
                            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $subject->name ?? 'Unknown Subject' }}</h4>
                                    @if($subjectAverage)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $subjectAverage >= 90 ? 'bg-green-100 text-green-800' : 
                                               ($subjectAverage >= 80 ? 'bg-blue-100 text-blue-800' : 
                                               ($subjectAverage >= 70 ? 'bg-yellow-100 text-yellow-800' : 
                                               ($subjectAverage >= 60 ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) }}">
                                            {{ number_format($subjectAverage, 1) }}%
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $subjectGrades->count() }} assignments
                                </p>
                                @if($subject)
                                    <a href="{{ route('student.grades.by-subject', $subject) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View all grades →
                                    </a>
                                @endif
                            </div>
                        @endforeach
                        
                        @if($gradesBySubject->count() == 0)
                            <div class="col-span-3 text-center py-8 bg-gray-50 rounded-lg">
                                <p class="text-gray-500">No subject grades available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection