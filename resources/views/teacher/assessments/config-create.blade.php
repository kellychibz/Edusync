@extends('layouts.app')

@section('title', 'Configure Assessment - ' . $subject->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Configure Assessment</h2>
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

                <div class="max-w-2xl">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800">Assessment Configuration</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    Set the percentage weights for Continuous Assessment (CA) and Final Exam.
                                    The total must equal 100%.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('teacher.assessments.config.store') }}" method="POST">
                        @csrf
                        
                        <input type="hidden" name="class_group_id" value="{{ $classGroup->id }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="academic_year_id" value="{{ $currentAcademicYear->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- CA Percentage -->
                            <div>
                                <label for="ca_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                    Continuous Assessment (CA) Percentage
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" 
                                           name="ca_percentage" 
                                           id="ca_percentage"
                                           min="0" 
                                           max="100" 
                                           value="40"
                                           class="block w-full pr-12 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5"
                                           required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Percentage for Term 1 & 2 assessments
                                </p>
                                @error('ca_percentage')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Final Exam Percentage -->
                            <div>
                                <label for="final_exam_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                    Final Exam Percentage
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" 
                                           name="final_exam_percentage" 
                                           id="final_exam_percentage"
                                           min="0" 
                                           max="100" 
                                           value="60"
                                           class="block w-full pr-12 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5"
                                           required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Percentage for Term 3 final exam
                                </p>
                                @error('final_exam_percentage')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Total Display -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total Percentage:</span>
                                <span id="total-percentage" class="text-lg font-semibold text-gray-900">100%</span>
                            </div>
                            <div id="percentage-error" class="mt-2 text-sm text-red-600 hidden">
                                Total percentage must equal 100%
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('teacher.assessments.class', $classGroup->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" 
                                    id="submit-button"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const caInput = document.getElementById('ca_percentage');
    const finalInput = document.getElementById('final_exam_percentage');
    const totalDisplay = document.getElementById('total-percentage');
    const errorDisplay = document.getElementById('percentage-error');
    const submitButton = document.getElementById('submit-button');

    function updateTotal() {
        const caValue = parseInt(caInput.value) || 0;
        const finalValue = parseInt(finalInput.value) || 0;
        const total = caValue + finalValue;
        
        totalDisplay.textContent = total + '%';
        
        if (total === 100) {
            totalDisplay.classList.remove('text-red-600');
            totalDisplay.classList.add('text-green-600');
            errorDisplay.classList.add('hidden');
            submitButton.disabled = false;
        } else {
            totalDisplay.classList.remove('text-green-600');
            totalDisplay.classList.add('text-red-600');
            errorDisplay.classList.remove('hidden');
            submitButton.disabled = true;
        }
    }

    caInput.addEventListener('input', updateTotal);
    finalInput.addEventListener('input', updateTotal);
    
    // Initial calculation
    updateTotal();
});
</script>
@endsection