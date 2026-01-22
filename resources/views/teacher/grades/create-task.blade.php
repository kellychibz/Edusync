@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Create Grading Task</h2>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-blue-800 font-medium">Creating task for:</p>
                            <p class="text-blue-600">{{ $classGroup->name }} - {{ $subject->name }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('teacher.grades.task.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_group_id" value="{{ $classGroup->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Task Title *</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Task Type *</label>
                                <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror" 
                                        id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                    <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="test" {{ old('type') == 'test' ? 'selected' : '' }}>Test</option>
                                    <option value="exam" {{ old('type') == 'exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="project" {{ old('type') == 'project' ? 'selected' : '' }}>Project</option>
                                    <option value="homework" {{ old('type') == 'homework' ? 'selected' : '' }}>Homework</option>
                                    <option value="participation" {{ old('type') == 'participation' ? 'selected' : '' }}>Participation</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="max_score" class="block text-sm font-medium text-gray-700">Maximum Score *</label>
                                <input type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('max_score') border-red-500 @enderror" 
                                       id="max_score" name="max_score" value="{{ old('max_score', 100) }}" required>
                                @error('max_score')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('due_date') border-red-500 @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date') }}">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('teacher.grades.class', $classGroup) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Create Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection