@extends('layouts.app')

@section('content')
<div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Manage Class Assignments: {{ $teacher->user->name }}</h2>
            <a href="{{ route('admin.teachers.show', $teacher) }}" class="text-gray-600 hover:text-gray-900">
                &larr; Back to profile
            </a>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('admin.teachers.update-assignments', $teacher) }}">
                @csrf
                
                <div id="assignments-container">
                    <!-- Existing assignments will be loaded here -->
                    @foreach($teacher->classSubjectAssignments as $assignment)
                    <div class="assignment-row grid grid-cols-1 gap-4 mb-4 p-4 border border-gray-200 rounded-lg md:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class</label>
                            <select name="assignments[{{ $loop->index }}][class_group_id]" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Class</option>
                                @foreach($classGroups as $classGroup)
                                <option value="{{ $classGroup->id }}" {{ $assignment->class_group_id == $classGroup->id ? 'selected' : '' }}>
                                    {{ $classGroup->name }} - {{ $classGroup->gradeLevel->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subject</label>
                            <select name="assignments[{{ $loop->index }}][subject_id]" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Periods/Week</label>
                            <input type="number" name="assignments[{{ $loop->index }}][periods_per_week]" 
                                   value="{{ $assignment->periods_per_week }}" min="1" max="20"
                                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" class="px-4 py-2 text-red-600 bg-red-100 rounded-md hover:bg-red-200 remove-assignment">
                                Remove
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" id="add-assignment" class="px-4 py-2 text-green-600 bg-green-100 rounded-md hover:bg-green-200">
                        + Add Assignment
                    </button>
                    
                    <div class="space-x-3">
                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Save Assignments
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let assignmentCount = {{ $teacher->classSubjectAssignments->count() }};
    
    document.getElementById('add-assignment').addEventListener('click', function() {
        const container = document.getElementById('assignments-container');
        const newRow = document.createElement('div');
        newRow.className = 'assignment-row grid grid-cols-1 gap-4 mb-4 p-4 border border-gray-200 rounded-lg md:grid-cols-4';
        
        newRow.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <select name="assignments[${assignmentCount}][class_group_id]" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Class</option>
                    @foreach($classGroups as $classGroup)
                    <option value="{{ $classGroup->id }}">{{ $classGroup->name }} - {{ $classGroup->gradeLevel->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="assignments[${assignmentCount}][subject_id]" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Periods/Week</label>
                <input type="number" name="assignments[${assignmentCount}][periods_per_week]" value="5" min="1" max="20"
                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div class="flex items-end">
                <button type="button" class="px-4 py-2 text-red-600 bg-red-100 rounded-md hover:bg-red-200 remove-assignment">
                    Remove
                </button>
            </div>
        `;
        
        container.appendChild(newRow);
        assignmentCount++;
    });

    // Remove assignment row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-assignment')) {
            e.target.closest('.assignment-row').remove();
        }
    });
});
</script>
@endsection