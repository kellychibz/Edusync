
@extends('layouts.app')

@section('title', 'Add Grade')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Add New Grade</h1>
            <p class="text-gray-600">Enter grade information for a student</p>
        </div>

        <!-- Grade Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('teacher.grades.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Class Group Selection -->
                    <div>
                        <label for="class_group_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Class Group *
                        </label>
                        <select id="class_group_id" name="class_group_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                onchange="loadStudents(this.value)">
                            <option value="">Select a class</option>
                            @foreach($classGroups as $classGroup)
                                <option value="{{ $classGroup->id }}" {{ old('class_group_id') == $classGroup->id ? 'selected' : '' }}>
                                    {{ $classGroup->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_group_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Selection -->
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Student *
                        </label>
                        <select id="student_id" name="student_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                disabled>
                            <option value="">Select a student</option>
                        </select>
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Selection -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject *
                        </label>
                        <select id="subject_id" name="subject_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assignment Type -->
                    <div>
                        <label for="assignment_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Assignment Type *
                        </label>
                        <select id="assignment_type" name="assignment_type" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select type</option>
                            <option value="homework" {{ old('assignment_type') == 'homework' ? 'selected' : '' }}>Homework</option>
                            <option value="quiz" {{ old('assignment_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="test" {{ old('assignment_type') == 'test' ? 'selected' : '' }}>Test</option>
                            <option value="project" {{ old('assignment_type') == 'project' ? 'selected' : '' }}>Project</option>
                            <option value="participation" {{ old('assignment_type') == 'participation' ? 'selected' : '' }}>Participation</option>
                        </select>
                        @error('assignment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assignment Name -->
                    <div class="md:col-span-2">
                        <label for="assignment_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Assignment Name *
                        </label>
                        <input type="text" id="assignment_name" name="assignment_name" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g., Chapter 1 Quiz, Final Exam"
                               value="{{ old('assignment_name') }}">
                        @error('assignment_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grade -->
                    <div class="md:col-span-2">
                        <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">
                            Grade (%) *
                        </label>
                        <input type="number" id="grade" name="grade" min="0" max="100" step="0.1" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Enter grade percentage (0-100)"
                               value="{{ old('grade') }}">
                        @error('grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comments -->
                    <div class="md:col-span-2">
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            Comments (Optional)
                        </label>
                        <textarea id="comments" name="comments" rows="3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Any additional comments about this grade">{{ old('comments') }}</textarea>
                        @error('comments')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('teacher.grades.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Grade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadStudents(classGroupId) {
    const studentSelect = document.getElementById('student_id');
    
    if (!classGroupId) {
        studentSelect.innerHTML = '<option value="">Select a student</option>';
        studentSelect.disabled = true;
        return;
    }

    // Show loading
    studentSelect.innerHTML = '<option value="">Loading students...</option>';
    studentSelect.disabled = true;

    // Fetch students from the class
    fetch(`/teacher/get-students/${classGroupId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(students => {
            studentSelect.innerHTML = '<option value="">Select a student</option>';
            students.forEach(student => {
                studentSelect.innerHTML += `<option value="${student.id}">${student.user.name}</option>`;
            });
            studentSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading students:', error);
            studentSelect.innerHTML = '<option value="">Error loading students. Please try again.</option>';
        });
}

// Initialize form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const studentSelect = document.getElementById('student_id');
        if (studentSelect.disabled || !studentSelect.value) {
            e.preventDefault();
            alert('Please select a class and then select a student.');
            return false;
        }
    });
});
</script>
@endsection