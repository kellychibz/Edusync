@extends('layouts.app')

@section('title', 'Create Term Assessment - ' . $subject->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Error Display -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                        </div>
                        <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- CA Allocation Display -->
                @if(!$assessmentConfig)
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-red-800">Assessment Configuration Required</h4>
                                <p class="text-sm text-red-700 mt-1">
                                    You need to configure assessment percentages before creating term assessments.
                                    <a href="{{ route('teacher.assessments.config.create', ['classGroup' => $classGroup->id, 'subject' => $subject->id]) }}" 
                                       class="font-medium underline">
                                        Configure Assessment Percentages
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- CA Allocation Info Box -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Assessment Configuration
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                            <div class="text-center p-3 bg-blue-100 rounded-lg">
                                <p class="text-sm font-medium text-blue-600">Continuous Assessment (CA)</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $caPercentage }}%</p>
                            </div>
                            <div class="text-center p-3 bg-orange-100 rounded-lg">
                                <p class="text-sm font-medium text-orange-600">Final Exam</p>
                                <p class="text-2xl font-bold text-orange-900">{{ $finalExamPercentage }}%</p>
                            </div>
                            <div class="text-center p-3 bg-green-100 rounded-lg">
                                <p class="text-sm font-medium text-green-600">Term Allocation</p>
                                <p class="text-2xl font-bold text-green-900">{{ $termAllocation }}% per term</p>
                            </div>
                        </div>
                        <div class="text-sm text-blue-700">
                            <p class="mb-1"><strong>Allocation Rules:</strong></p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>CA {{ $caPercentage }}% is split equally between Term 1 and Term 2</li>
                                <li>Each term gets <strong>{{ $termAllocation }}%</strong> of the final grade</li>
                                <li>Term 3 is for Final Exam only ({{ $finalExamPercentage }}%)</li>
                                <li>Each term assessment uses the full term allocation</li>
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Create Term Assessment</h2>
                        <p class="text-gray-600 mt-1">{{ $classGroup->name }} - {{ $subject->name }}</p>
                    </div>
                    <a href="{{ route('teacher.assessments.class', $classGroup->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Assessments
                    </a>
                </div>

                @if($assessmentConfig)
                <form action="{{ route('teacher.term-assessments.store') }}" method="POST" id="termAssessmentForm">
                    @csrf
                    
                    <input type="hidden" name="class_group_id" value="{{ $classGroup->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Term Selection -->
                        <div>
                            <label for="term_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Term *
                            </label>
                            <select name="term_id" id="term_id" required
                                    class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                                <option value="">Select Term</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term->id }}" {{ old('term_id') == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }} ({{ $term->start_date->format('M j') }} - {{ $term->end_date->format('M j, Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Due Date *
                            </label>
                            <input type="date" name="due_date" id="due_date" required
                                   class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5"
                                   value="{{ old('due_date') }}">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Assessment Title *
                        </label>
                        <input type="text" name="title" id="title" required
                               class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5"
                               placeholder="e.g., Term 1 Mathematics Test"
                               value="{{ old('title') }}">
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Optional description of the assessment...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="max_score" class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Score *
                        </label>
                        <input type="number" name="max_score" id="max_score" required min="1" max="1000"
                               class="block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5"
                               placeholder="e.g., 100" value="{{ old('max_score', 100) }}">
                    </div>

                    <!-- Task Selection Section -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Select Grade Tasks</h3>
                            <div class="text-sm font-medium text-gray-600">
                                Select tasks and set weights (should total 100%)
                                <span id="total-weight-display" class="ml-2 font-bold">Total: 0%</span>
                            </div>
                        </div>

                        @if($gradeTasks->count() > 0)
                            <div class="space-y-4" id="tasks-container">
                                @foreach($gradeTasks as $task)
                                    <div class="border border-gray-200 rounded-lg p-4 task-item">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">{{ $task->title }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    Type: {{ ucfirst($task->type) }} • 
                                                    Max Score: {{ $task->max_score }} •
                                                    Due: {{ $task->due_date ? $task->due_date->format('M j, Y') : 'No due date' }}
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-4 ml-4">
                                                <div class="w-32">
                                                    <input type="number" 
                                                           name="tasks[{{ $task->id }}][weight_percentage]" 
                                                           class="task-weight block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-1.5"
                                                           min="0" 
                                                           max="100" 
                                                           placeholder="Weight %"
                                                           value="{{ old('tasks.' . $task->id . '.weight_percentage', 0) }}">
                                                    <input type="hidden" name="tasks[{{ $task->id }}][grade_task_id]" value="{{ $task->id }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @error('tasks')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800">No grade tasks available</h4>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            You need to create grade tasks first before creating term assessments.
                                            <a href="{{ route('teacher.grades.task.create', $classGroup->id) }}" class="font-medium underline">
                                                Create Grade Tasks
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('teacher.assessments.class', $classGroup->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Assessment
                        </button>
                    </div>
                </form>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">Please configure assessment percentages first.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('termAssessmentForm');
    const taskWeights = document.querySelectorAll('.task-weight');
    const totalDisplay = document.getElementById('total-weight-display');
    
    function calculateTotalWeight() {
        let total = 0;
        taskWeights.forEach(weightInput => {
            const weight = parseInt(weightInput.value) || 0;
            total += weight;
        });
        return total;
    }
    
    function updateTotalDisplay() {
        const total = calculateTotalWeight();
        if (totalDisplay) {
            totalDisplay.textContent = `Total: ${total}%`;
            totalDisplay.style.color = total === 100 ? 'green' : 'red';
            totalDisplay.style.fontWeight = 'bold';
        }
    }
    
    // Real-time weight calculation
    taskWeights.forEach(input => {
        input.addEventListener('input', function() {
            updateTotalDisplay();
        });
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        const totalWeight = calculateTotalWeight();
        
        if (totalWeight !== 100) {
            e.preventDefault();
            alert(`Error: Total weight must be exactly 100%. Current total: ${totalWeight}%`);
            return false;
        }
    });
    
    // Initialize total display
    updateTotalDisplay();
});
</script>
@endsection