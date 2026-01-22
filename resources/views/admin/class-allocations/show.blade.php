@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $classGroup->name }}</h2>
                        <p class="text-gray-600 mt-1">
                            Class Management 
                            @if($classGroup->gradeLevel) • {{ $classGroup->gradeLevel->name }} @endif
                            @if($classGroup->section) • Section {{ $classGroup->section }} @endif
                        </p>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('admin.class-allocations.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            Back to Dashboard
                        </a>
                        <a href="{{ route('admin.class-groups.edit', $classGroup) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Edit Class
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Class Info & Add Student -->
                    <div class="lg:col-span-1">
                        <!-- Class Information -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Class Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Class Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $classGroup->name }}</dd>
                                </div>
                                @if($classGroup->section)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Section</dt>
                                    <dd class="text-sm text-gray-900">{{ $classGroup->section }}</dd>
                                </div>
                                @endif
                                @if($classGroup->gradeLevel)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Grade Level</dt>
                                    <dd class="text-sm text-gray-900">{{ $classGroup->gradeLevel->name }}</dd>
                                </div>
                                @endif
                                @if($classGroup->teacher)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Form Teacher</dt>
                                    <dd class="text-sm text-gray-900">{{ $classGroup->teacher->user->name }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $classGroup->students->count() }} / {{ $classGroup->capacity ?? 'No limit' }} students
                                    </dd>
                                </div>
                                @if($classGroup->capacity)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Availability</dt>
                                    <dd class="text-sm">
                                        @php
                                            $available = $classGroup->capacity - $classGroup->students->count();
                                        @endphp
                                        @if($available > 0)
                                            <span class="text-green-600 font-medium">{{ $available }} spots available</span>
                                        @else
                                            <span class="text-red-600 font-medium">Class is full</span>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Add Student to Class -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Add Student to Class</h3>
                            
                            @if($classGroup->capacity && $classGroup->students->count() >= $classGroup->capacity)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <p class="text-red-800 text-sm">Class has reached its capacity. Cannot add more students.</p>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('admin.class-allocations.add-student', $classGroup) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Select Student</label>
                                        <select name="student_id" id="student_id" required
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Choose a student...</option>
                                            @foreach($availableStudents as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->user->name }} 
                                                    ({{ $student->admission_number ?? $student->id }})
                                                    @if($student->classGroups->count() > 0)
                                                        - {{ $student->classGroups->count() }} class(es)
                                                    @else
                                                        - Unassigned
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                        Add to Class
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Student List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    Students in Class
                                    <span class="ml-2 text-sm text-gray-600 font-normal">
                                        ({{ $classGroup->students->count() }} students)
                                    </span>
                                </h3>
                            </div>

                            <div class="p-6">
                                @if($classGroup->students->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Other Classes</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($classGroup->students as $student)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                @if($student->photo)
                                                                    <img src="{{ asset('storage/' . $student->photo) }}" 
                                                                         alt="{{ $student->user->name }}" 
                                                                         class="h-10 w-10 rounded-full object-cover">
                                                                @else
                                                                    <div class="bg-blue-500 text-white rounded-full h-10 w-10 flex items-center justify-center">
                                                                        {{ substr($student->user->name, 0, 1) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                                                <div class="text-sm text-gray-500">
                                                                    {{ $student->admission_number ?? $student->id }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $student->user->email }}</div>
                                                        <div class="text-sm text-gray-500">{{ $student->parent_phone }}</div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm text-gray-900">
                                                            @php
                                                                $otherClasses = $student->classGroups->where('id', '!=', $classGroup->id);
                                                            @endphp
                                                            @if($otherClasses->count() > 0)
                                                                @foreach($otherClasses as $otherClass)
                                                                    <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mb-1 mr-1">
                                                                        {{ $otherClass->name }}
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <span class="text-gray-400 text-sm">No other classes</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <form action="{{ route('admin.class-allocations.remove-student', [$classGroup, $student]) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Remove {{ $student->user->name }} from this class?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-600 hover:text-red-900 transition">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">No students in this class</h3>
                                        <p class="mt-2 text-gray-500">Add students using the form on the left.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection