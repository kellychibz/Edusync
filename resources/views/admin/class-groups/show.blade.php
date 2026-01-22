{{-- resources/views/admin/class-groups/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">{{ $classGroup->name }}</h2>
                        <p class="text-gray-600">{{ $classGroup->class_code }}</p>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('admin.class-groups.edit', $classGroup) }}" 
                           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                            Edit Class
                        </a>
                        <a href="{{ route('admin.class-groups.manage-subjects', $classGroup) }}" 
                           class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition">
                            Manage Subjects
                        </a>
                        <a href="{{ route('admin.class-groups.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                            Back to Classes
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Class Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Total Students</p>
                                <p class="text-2xl font-semibold text-blue-900">{{ $classGroup->students->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Subjects</p>
                                <p class="text-2xl font-semibold text-green-900">{{ $classGroup->subjects->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Grade Level</p>
                                <p class="text-2xl font-semibold text-purple-900">{{ $classGroup->gradeLevel->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-orange-600">Form Teacher</p>
                                <p class="text-lg font-semibold text-orange-900">
                                    {{ $classGroup->form_teacher }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Class Details Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Class Information & Stream -->
                    <div class="space-y-6">
                        <!-- Class Information -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold">Class Information</h3>
                            </div>
                            <div class="p-6">
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Class Name</dt>
                                        <dd class="text-sm text-gray-900">{{ $classGroup->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Class Code</dt>
                                        <dd class="text-sm text-gray-900">{{ $classGroup->class_code }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Grade Level</dt>
                                        <dd class="text-sm text-gray-900">{{ $classGroup->gradeLevel->name ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                                        <dd class="text-sm text-gray-900">
                                            {{ $classGroup->capacity ? $classGroup->capacity : 'No limit' }}
                                        </dd>
                                    </div>
                                    @if($classGroup->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="text-sm text-gray-900">{{ $classGroup->description }}</dd>
                                    </div>
                                    @endif
                                </dl>
                            </div>
                        </div>

                        <!-- Stream Information -->
                        @if($classGroup->stream)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold">Stream Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-900">{{ $classGroup->stream->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $classGroup->stream->code }}</p>
                                    @if($classGroup->stream->description)
                                        <p class="text-sm text-gray-500 mt-2">{{ $classGroup->stream->description }}</p>
                                    @endif
                                </div>
                                
                                <div class="space-y-3">
                                    <div>
                                        <h5 class="text-sm font-medium text-blue-800 mb-2">Core Subjects</h5>
                                        <div class="space-y-1">
                                            @foreach($classGroup->stream->core_subjects_list as $subject)
                                                <div class="flex items-center text-sm">
                                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                                    {{ $subject->name }}
                                                    @if($subject->department)
                                                        <span class="text-xs text-gray-500 ml-2">({{ $subject->department->name }})</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @if($classGroup->stream->optional_subjects_list->count() > 0)
                                    <div>
                                        <h5 class="text-sm font-medium text-green-800 mb-2">Optional Subjects</h5>
                                        <div class="space-y-1">
                                            @foreach($classGroup->stream->optional_subjects_list as $subject)
                                                <div class="flex items-center text-sm">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                    {{ $subject->name }}
                                                    @if($subject->department)
                                                        <span class="text-xs text-gray-500 ml-2">({{ $subject->department->name }})</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Middle Column - Students -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Students ({{ $classGroup->students->count() }})</h3>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-500">
                                Manage Students
                            </a>
                        </div>
                        <div class="p-6">
                            @if($classGroup->students->count() > 0)
                                <div class="space-y-3 max-h-96 overflow-y-auto">
                                    @foreach($classGroup->students as $student)
                                        <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">
                                                        {{ substr($student->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $student->user->name }}</p>
                                                    <p class="text-sm text-gray-600">Student ID: {{ $student->id }}</p>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                {{ $student->admission_number ?? 'No ID' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No students assigned to this class.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Subjects -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Subjects ({{ $classGroup->classSubjects->count() }})</h3>
                            <a href="{{ route('admin.class-groups.manage-subjects', $classGroup) }}" 
                            class="text-sm text-purple-600 hover:text-purple-500">
                                Manage Subjects
                            </a>
                        </div>
                        <div class="p-6">
                            @if($classGroup->classSubjects->count() > 0)
                                <div class="space-y-3">
                                    @foreach($classGroup->classSubjects as $classSubject)
                                        <div class="p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $classSubject->subject->name }}</p>
                                                    @if($classSubject->teacher_id && $classSubject->teacher)
                                                        <p class="text-sm text-gray-600">
                                                            Teacher: {{ $classSubject->teacher->user->name }}
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-gray-400">No teacher assigned</p>
                                                    @endif
                                                </div>
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                    {{ $classSubject->periods_per_week }} periods/week
                                                </span>
                                            </div>
                                            @if($classSubject->subject->department)
                                                <div class="mt-2">
                                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                        {{ $classSubject->subject->department->name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No subjects assigned to this class.</p>
                            @endif
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection