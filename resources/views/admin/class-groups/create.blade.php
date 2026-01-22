{{-- resources/views/admin/class-groups/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Create New Class</h2>
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

                <form action="{{ route('admin.class-groups.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       placeholder="e.g., Grade 11 Science A"
                                       required>
                            </div>

                            <div>
                                <label for="class_code" class="block text-sm font-medium text-gray-700">Class Code</label>
                                <input type="text" name="class_code" id="class_code" 
                                       value="{{ old('class_code') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       placeholder="e.g., 11A, 11B"
                                       required>
                            </div>

                            <div>
                                <label for="grade_level_id" class="block text-sm font-medium text-gray-700">Grade Level</label>
                                <select name="grade_level_id" id="grade_level_id" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                    <option value="">Select Grade Level</option>
                                    @foreach($gradeLevels as $grade)
                                        <option value="{{ $grade->id }}" {{ old('grade_level_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                <input type="number" name="capacity" id="capacity" 
                                       value="{{ old('capacity') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       placeholder="e.g., 30"
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
                                        <option value="{{ $stream->id }}" {{ old('stream_id') == $stream->id ? 'selected' : '' }}
                                                data-subjects="{{ json_encode($stream->core_subjects) }}">
                                            {{ $stream->name }} ({{ $stream->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500" id="stream-description">
                                    Select a stream to see its description
                                </p>
                            </div>

                            <div>
                                <label for="teacher_id" class="block text-sm font-medium text-gray-700">Form Teacher</label>
                                <select name="teacher_id" id="teacher_id" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">No Form Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                          placeholder="Optional class description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Stream Subjects Preview -->
                    <div id="subjects-preview" class="mt-6 p-4 bg-gray-50 rounded-lg hidden">
                        <h4 class="text-lg font-semibold mb-3">Stream Subjects</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-blue-800 mb-2">Core Subjects</h5>
                                <div id="core-subjects-list" class="space-y-1"></div>
                            </div>
                            <div>
                                <h5 class="font-medium text-green-800 mb-2">Optional Subjects</h5>
                                <div id="optional-subjects-list" class="space-y-1"></div>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">
                            These subjects will be automatically assigned to the class when created.
                        </p>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                            Create Class
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const streamSelect = document.getElementById('stream_id');
    const subjectsPreview = document.getElementById('subjects-preview');
    const coreSubjectsList = document.getElementById('core-subjects-list');
    const optionalSubjectsList = document.getElementById('optional-subjects-list');
    const streamDescription = document.getElementById('stream-description');

    const streams = @json($streams->keyBy('id')->toArray());
    const subjects = @json($subjects->keyBy('id')->toArray());

    streamSelect.addEventListener('change', function() {
        const streamId = this.value;
        
        if (streamId && streams[streamId]) {
            const stream = streams[streamId];
            
            // Update stream description
            streamDescription.textContent = stream.description || 'No description available.';
            
            // Show subjects preview
            subjectsPreview.classList.remove('hidden');
            
            // Clear previous lists
            coreSubjectsList.innerHTML = '';
            optionalSubjectsList.innerHTML = '';
            
            // Populate core subjects
            if (stream.core_subjects && stream.core_subjects.length > 0) {
                stream.core_subjects.forEach(subjectId => {
                    if (subjects[subjectId]) {
                        const subject = subjects[subjectId];
                        const div = document.createElement('div');
                        div.className = 'flex items-center text-sm';
                        div.innerHTML = `
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            ${subject.name}
                            ${subject.department ? `<span class="text-xs text-gray-500 ml-2">(${subject.department.name})</span>` : ''}
                        `;
                        coreSubjectsList.appendChild(div);
                    }
                });
            } else {
                coreSubjectsList.innerHTML = '<p class="text-sm text-gray-500">No core subjects defined.</p>';
            }
            
            // Populate optional subjects
            if (stream.optional_subjects && stream.optional_subjects.length > 0) {
                stream.optional_subjects.forEach(subjectId => {
                    if (subjects[subjectId]) {
                        const subject = subjects[subjectId];
                        const div = document.createElement('div');
                        div.className = 'flex items-center text-sm';
                        div.innerHTML = `
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            ${subject.name}
                            ${subject.department ? `<span class="text-xs text-gray-500 ml-2">(${subject.department.name})</span>` : ''}
                        `;
                        optionalSubjectsList.appendChild(div);
                    }
                });
            } else {
                optionalSubjectsList.innerHTML = '<p class="text-sm text-gray-500">No optional subjects defined.</p>';
            }
        } else {
            subjectsPreview.classList.add('hidden');
            streamDescription.textContent = 'Select a stream to see its description';
        }
    });

    // Trigger change event if there's a previously selected value
    if (streamSelect.value) {
        streamSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection