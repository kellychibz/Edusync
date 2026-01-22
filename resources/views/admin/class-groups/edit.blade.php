{{-- resources/views/admin/class-groups/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Edit Class: {{ $classGroup->name }}</h2>
                    <a href="{{ route('admin.class-groups.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                        Back to Classes
                    </a>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.class-groups.update', $classGroup) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $classGroup->name) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       required>
                            </div>

                            <div>
                                <label for="class_code" class="block text-sm font-medium text-gray-700">Class Code</label>
                                <input type="text" name="class_code" id="class_code" 
                                       value="{{ old('class_code', $classGroup->class_code) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       required>
                            </div>

                            <div>
                                <label for="grade_level_id" class="block text-sm font-medium text-gray-700">Grade Level</label>
                                <select name="grade_level_id" id="grade_level_id" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                    <option value="">Select Grade Level</option>
                                    @foreach($gradeLevels as $grade)
                                        <option value="{{ $grade->id }}" {{ (old('grade_level_id') ?? $classGroup->grade_level_id) == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                <input type="number" name="capacity" id="capacity" 
                                       value="{{ old('capacity', $classGroup->capacity) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       min="1">
                            </div>
                        </div>

                        <!-- Stream & Teacher Assignment -->
                        <div class="space-y-4">
                            <div>
                                <label for="stream_id" class="block text-sm font-medium text-gray-700">Stream</label>
                                <select name="stream_id" id="stream_id" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                    <option value="">Select Stream</option>
                                    @foreach($streams as $stream)
                                        <option value="{{ $stream->id }}" {{ (old('stream_id') ?? $classGroup->stream_id) == $stream->id ? 'selected' : '' }}>
                                            {{ $stream->name }} ({{ $stream->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="teacher_id" class="block text-sm font-medium text-gray-700">Form Teacher</label>
                                <select name="teacher_id" id="teacher_id" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">No Form Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ (old('teacher_id') ?? $classGroup->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }}
                                            @if($teacher->department)
                                                - {{ $teacher->department->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ old('description', $classGroup->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Current Class Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-lg font-semibold mb-2">Current Class Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Students:</span> {{ $classGroup->students->count() }}
                            </div>
                            <div>
                                <span class="font-medium">Subjects:</span> {{ $classGroup->subjects->count() }}
                            </div>
                            <div>
                                <span class="font-medium">Current Stream:</span> 
                                {{ $classGroup->stream ? $classGroup->stream->name : 'Not set' }}
                            </div>
                            <div>
                                <span class="font-medium">Grade Level:</span> 
                                {{ $classGroup->gradeLevel->name ?? 'Not set' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                            Update Class
                        </button>
                        <a href="{{ route('admin.class-groups.index') }}" 
                           class="ml-4 bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection