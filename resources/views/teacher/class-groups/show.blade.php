@extends('layouts.app')

@section('title', $classGroup->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $classGroup->name }}</h1>
                    <p class="text-gray-600">Class Details and Student Information</p>
                </div>
                <a href="{{ route('teacher.grades.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Grade
                </a>
            </div>
        </div>

        <!-- Class Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Students List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Students ({{ $classGroup->students->count() }})</h2>
                    </div>
                    <div class="p-6">
                        @if($classGroup->students->count() > 0)
                            <div class="space-y-4">
                                @foreach($classGroup->students as $student)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}" 
                                                     alt="{{ $student->user->name }}"
                                                     class="w-10 h-10 rounded-full mr-4">
                                            @else
                                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                                                    <span class="text-gray-600 text-sm font-medium">
                                                        {{ substr($student->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $student->user->name }}</h3>
                                                <p class="text-sm text-gray-600">Student ID: {{ $student->student_id }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('teacher.grades.create') }}?student_id={{ $student->id }}" 
                                           class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                            Add Grade
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No students in this class.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Class Details & Recent Activity -->
            <div class="space-y-6">
                <!-- Class Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Class Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Class Name</p>
                                <p class="font-medium">{{ $classGroup->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Academic Year</p>
                                <p class="font-medium">{{ $classGroup->academic_year ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Subjects</p>
                                <div class="mt-1">
                                    @foreach($classGroup->subjects as $subject)
                                        <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                                            {{ $subject->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Grades -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Grades</h2>
                    </div>
                    <div class="p-6">
                        @if($recentGrades->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentGrades as $grade)
                                    <div class="flex items-center justify-between text-sm">
                                        <div>
                                            <span class="font-medium">{{ $grade->student->user->name }}</span>
                                            <span class="text-gray-600">- {{ $grade->subject->name }}</span>
                                        </div>
                                        <span class="px-2 py-1 bg-{{ $grade->grade >= 80 ? 'green' : ($grade->grade >= 70 ? 'yellow' : 'red') }}-100 text-{{ $grade->grade >= 80 ? 'green' : ($grade->grade >= 70 ? 'yellow' : 'red') }}-800 text-xs rounded">
                                            {{ $grade->grade }}%
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No recent grades.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection