@extends('layouts.app')

@section('title', 'Edit Assessment - ' . $termAssessment->title)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Term Assessment</h2>

                <!-- CA Allocation Info -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-blue-800">CA Allocation Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-blue-700">Term {{ $termAssessment->term->term_number }} Weight:</p>
                            <p class="text-lg font-semibold text-blue-900">{{ $termWeight }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Already Used (other assessments):</p>
                            <p class="text-lg font-semibold text-blue-900">{{ $termWeight - $availableAllocation - $currentAllocation }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700">Current Allocation:</p>
                            <p class="text-lg font-semibold text-blue-900">{{ $currentAllocation }}%</p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-blue-700 mb-1">
                            <span>CA Allocation Usage</span>
                            <span>{{ $termWeight - $availableAllocation }}% / {{ $termWeight }}%</span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ (($termWeight - $availableAllocation) / $termWeight) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form method="POST" action="{{ route('teacher.term-assessments.update', $termAssessment) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Assessment Title</label>
                                <input type="text" name="title" value="{{ old('title', $termAssessment->title) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                <textarea name="description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $termAssessment->description) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Maximum Score</label>
                                <input type="number" name="max_score" step="0.01" min="1" max="1000"
                                       value="{{ old('max_score', $termAssessment->max_score) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>
                        </div>

                        <!-- Term & Date -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Term</label>
                                <select name="term_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}" 
                                                {{ $termAssessment->term_id == $term->id ? 'selected' : '' }}>
                                            {{ $term->name }} ({{ $term->start_date->format('M j') }} - {{ $term->end_date->format('M j, Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date" 
                                       value="{{ old('due_date', $termAssessment->due_date->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <!-- CA Allocation -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    CA Allocation Percentage
                                    <span class="text-xs text-gray-500">(Max available: {{ $availableAllocation + $currentAllocation }}%)</span>
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="number" name="total_weight_percentage" 
                                           id="total_weight_percentage"
                                           value="{{ old('total_weight_percentage', $currentAllocation) }}"
                                           min="1" max="{{ $availableAllocation + $currentAllocation }}" step="0.1"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           required>
                                    <span class="ml-2 text-gray-500">%</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    This assessment contributes to the final grade. 
                                    Available in Term {{ $termAssessment->term->term_number }}: 
                                    <span class="font-medium">{{ $availableAllocation + $currentAllocation }}%</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Section -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Assessment Tasks</h3>
                            <span class="text-sm text-gray-600">Total weight must equal 100%</span>
                        </div>

                        <!-- Available Tasks -->
                        <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2">Available Grade Tasks</h4>
                            <p class="text-sm text-gray-600 mb-3">Select tasks to include in this assessment:</p>
                            
                            <div class="space-y-2 max-h-60 overflow-y-auto p-2">
                                @foreach($gradeTasks as $gradeTask)
                                    @php
                                        $isSelected = $currentTasks->contains('grade_task_id', $gradeTask->id);
                                    @endphp
                                    <div class="flex items-center p-2 hover:bg-gray-50 rounded">
                                        <input type="checkbox" 
                                               name="tasks[{{ $gradeTask->id }}][grade_task_id]" 
                                               value="{{ $gradeTask->id }}"
                                               id="task_{{ $gradeTask->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               {{ $isSelected ? 'checked' : '' }}>
                                        <label for="task_{{ $gradeTask->id }}" class="ml-2 flex-1 cursor-pointer">
                                            <div class="font-medium text-gray-900">{{ $gradeTask->title }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ ucfirst($gradeTask->type) }} • 
                                                Max: {{ $gradeTask->max_score }}
                                            </div>
                                        </label>
                                        <div class="w-32">
                                            @if($isSelected)
                                                @php
                                                    $currentTask = $currentTasks->firstWhere('grade_task_id', $gradeTask->id);
                                                @endphp
                                                <input type="number" 
                                                       name="tasks[{{ $gradeTask->id }}][weight_percentage]"
                                                       value="{{ old('tasks.' . $gradeTask->id . '.weight_percentage', $currentTask->weight_percentage) }}"
                                                       min="0" max="100" step="1"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right"
                                                       placeholder="Weight %">
                                            @else
                                                <input type="number" 
                                                       name="tasks[{{ $gradeTask->id }}][weight_percentage]"
                                                       value="0"
                                                       min="0" max="100" step="1"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right"
                                                       placeholder="Weight %">
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Weight Summary -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Total Task Weight:</span>
                                <span id="total_weight_display" class="text-xl font-bold text-gray-900">0%</span>
                            </div>
                            <div class="mt-2">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="weight_progress" class="h-full bg-green-500 rounded-full" style="width: 0%"></div>
                                </div>
                                <p id="weight_message" class="mt-1 text-sm text-gray-600 text-center">
                                    Add tasks and assign weights
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">

                            <form method="POST" action="{{ route('teacher.term-assessments.destroy', $termAssessment) }}" 
                                    class="inline" 
                                    onsubmit="return confirm('Are you sure you want to delete this assessment? This will also delete all associated tasks and scores.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete Assessment
                                    </button>
                                </form>
                            


                            <a href="{{ route('teacher.term-assessments.show', $termAssessment) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Assessment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Weight Calculation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightInputs = document.querySelectorAll('input[name^="tasks["][name$="[weight_percentage]"]');
    const totalWeightDisplay = document.getElementById('total_weight_display');
    const weightProgress = document.getElementById('weight_progress');
    const weightMessage = document.getElementById('weight_message');
    
    function calculateTotalWeight() {
        let total = 0;
        weightInputs.forEach(input => {
            if (input.closest('div').querySelector('input[type="checkbox"]').checked) {
                const value = parseFloat(input.value) || 0;
                total += value;
            }
        });
        
        totalWeightDisplay.textContent = total.toFixed(0) + '%';
        weightProgress.style.width = total + '%';
        
        if (total === 100) {
            weightProgress.classList.remove('bg-red-500', 'bg-yellow-500');
            weightProgress.classList.add('bg-green-500');
            weightMessage.textContent = '✅ Perfect! Total weight is 100%';
            weightMessage.className = 'mt-1 text-sm text-green-600 text-center';
        } else if (total > 100) {
            weightProgress.classList.remove('bg-green-500', 'bg-yellow-500');
            weightProgress.classList.add('bg-red-500');
            weightMessage.textContent = `❌ Exceeds 100% by ${(total - 100).toFixed(0)}%`;
            weightMessage.className = 'mt-1 text-sm text-red-600 text-center';
        } else {
            weightProgress.classList.remove('bg-green-500', 'bg-red-500');
            weightProgress.classList.add('bg-yellow-500');
            weightMessage.textContent = `⚠️ ${(100 - total).toFixed(0)}% remaining to reach 100%`;
            weightMessage.className = 'mt-1 text-sm text-yellow-600 text-center';
        }
    }
    
    // Add event listeners
    weightInputs.forEach(input => {
        input.addEventListener('input', calculateTotalWeight);
        // Also listen to checkbox changes
        const checkbox = input.closest('div').querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                input.value = 0;
            }
            calculateTotalWeight();
        });
    });
    
    // Initial calculation
    calculateTotalWeight();
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        calculateTotalWeight();
        const total = parseFloat(totalWeightDisplay.textContent);
        const caAllocation = parseFloat(document.getElementById('total_weight_percentage').value);
        
        if (total !== 100) {
            e.preventDefault();
            alert('Total task weight must equal 100%. Current total: ' + total + '%');
            return false;
        }
        
        if (caAllocation <= 0 || caAllocation > {{ $availableAllocation + $currentAllocation }}) {
            e.preventDefault();
            alert('CA allocation must be between 1% and {{ $availableAllocation + $currentAllocation }}%');
            return false;
        }
        
        return true;
    });
});
</script>
@endsection