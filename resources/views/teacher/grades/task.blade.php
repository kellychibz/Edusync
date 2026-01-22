@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Manage Grades: {{ $task->title }}</h2>
                        <p class="text-gray-600 mt-1">{{ $task->classGroup->name }} - {{ $task->subject->name }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-2">
                        <button type="button" id="saveAllBtn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-save mr-2"></i>
                            Save All Changes
                        </button>
                        <a href="{{ route('teacher.grades.class', $task->classGroup) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back to Class
                        </a>
                    </div>
                </div>

                <!-- Task Information -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 md:mb-0">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Class</p>
                                <p class="text-sm text-gray-900">{{ $task->classGroup->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Subject</p>
                                <p class="text-sm text-gray-900">{{ $task->subject->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Type</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    {{ $task->type }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Max Score</p>
                                <p class="text-sm text-gray-900">{{ $task->max_score }}</p>
                            </div>
                        </div>
                        <button type="button" id="editTaskBtn" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-edit mr-1"></i> Edit Task
                        </button>
                    </div>
                    @if($task->description)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-500">Description</p>
                            <p class="text-sm text-gray-900">{{ $task->description }}</p>
                        </div>
                    @endif
                    @if($task->due_date)
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-500">Due Date</p>
                            <p class="text-sm text-gray-900">{{ $task->due_date->format('F j, Y') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Grades Table -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Student Grades</h3>
                        <p class="text-sm text-gray-600 mt-1">Enter scores and comments for each student. Changes are saved individually or use "Save All Changes".</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="gradesTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Number</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        @php
                                            $gradeEntry = $student->gradeEntries->first();
                                            $currentScore = $gradeEntry->score ?? null;
                                            $currentPercentage = $gradeEntry->percentage ?? null;
                                            $currentGradeLetter = $gradeEntry->grade_letter ?? null;
                                        @endphp
                                        <tr id="student-{{ $student->id }}" class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $student->admission_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="{{ $task->max_score }}"
                                                       class="w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 score-input" 
                                                       data-student-id="{{ $student->id }}"
                                                       value="{{ $currentScore }}"
                                                       placeholder="Enter score">
                                                <div class="text-xs text-red-600 mt-1 max-score-warning" id="max-warning-{{ $student->id }}" style="display: none;">
                                                    Max: {{ $task->max_score }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 percentage-cell" id="percentage-{{ $student->id }}">
                                                @if($currentPercentage !== null)
                                                    {{ number_format($currentPercentage, 1) }}%
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 grade-letter-cell" id="grade-letter-{{ $student->id }}">
                                                @if($currentGradeLetter)
                                                    @php
                                                        $badgeClass = match($currentGradeLetter) {
                                                            'A' => 'bg-green-100 text-green-800',
                                                            'B' => 'bg-blue-100 text-blue-800',
                                                            'C' => 'bg-yellow-100 text-yellow-800',
                                                            'D' => 'bg-orange-100 text-orange-800',
                                                            'F' => 'bg-red-100 text-red-800',
                                                            default => 'bg-gray-100 text-gray-800',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                        {{ $currentGradeLetter }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <textarea class="w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 comments-input" 
                                                          data-student-id="{{ $student->id }}"
                                                          rows="1" 
                                                          placeholder="Comments">{{ $gradeEntry->comments ?? '' }}</textarea>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 last-updated" id="updated-{{ $student->id }}">
                                                @if($gradeEntry && $gradeEntry->graded_at)
                                                    {{ $gradeEntry->graded_at->format('M j, Y g:i A') }}
                                                @else
                                                    Not graded
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button type="button" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 save-single-grade" 
                                                        data-student-id="{{ $student->id }}">
                                                    <i class="fas fa-save mr-1"></i> Save
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="fixed inset-0 overflow-y-auto" style="display: none;" id="editTaskModal" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="editTaskModalLabel">
                        Edit Task
                    </h3>
                    <form action="{{ route('teacher.grades.task.update', $task) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label for="edit_title" class="block text-sm font-medium text-gray-700">Task Title</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                       id="edit_title" name="title" value="{{ $task->title }}" required>
                            </div>
                            <div>
                                <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                          id="edit_description" name="description" rows="3">{{ $task->description }}</textarea>
                            </div>
                            <div>
                                <label for="edit_max_score" class="block text-sm font-medium text-gray-700">Maximum Score</label>
                                <input type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                       id="edit_max_score" name="max_score" value="{{ $task->max_score }}" required>
                            </div>
                            <div>
                                <label for="edit_due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                       id="edit_due_date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Update Task
                            </button>
                            <button type="button" onclick="hideEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .score-input:focus, .comments-input:focus {
        border-color: #3b82f6;
        ring-width: 2px;
        ring-color: #3b82f6;
    }
    
    .score-input.invalid {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    
    .save-single-grade:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .custom-alert {
        animation: slideIn 0.3s ease-out;
        z-index: 10000;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush

<script>
    // Global variables
    const MAX_SCORE = {{ $task->max_score }};
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const GRADE_STORE_URL = '{{ route("teacher.grades.entry.store", $task) }}';

    console.log('🚀 Grade Management Initialized');
    console.log('📊 Max Score:', MAX_SCORE);
    console.log('🔗 Store URL:', GRADE_STORE_URL);
    console.log('🆔 Task ID:', {{ $task->id }});

    // Modal functions
    function showEditModal() {
        document.getElementById('editTaskModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function hideEditModal() {
        document.getElementById('editTaskModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('editTaskModal');
        if (event.target === modal) {
            hideEditModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOM Loaded - Initializing event listeners');
        
        // Initialize all grade calculations on page load
        initializeGradeCalculations();
        
        // Save All button
        const saveAllBtn = document.getElementById('saveAllBtn');
        if (saveAllBtn) {
            saveAllBtn.addEventListener('click', saveAllGrades);
            console.log('✅ Save All button listener added');
        }
        
        // Edit Task button
        const editTaskBtn = document.getElementById('editTaskBtn');
        if (editTaskBtn) {
            editTaskBtn.addEventListener('click', showEditModal);
            console.log('✅ Edit Task button listener added');
        }
        
        // Individual save buttons
        const saveButtons = document.querySelectorAll('.save-single-grade');
        console.log(`✅ Found ${saveButtons.length} individual save buttons`);
        
        saveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.dataset.studentId;
                console.log('💾 Individual save button clicked for student:', studentId);
                saveGrade(studentId);
            });
        });
        
        // Auto-calculate percentage when score changes
        const scoreInputs = document.querySelectorAll('.score-input');
        console.log(`✅ Found ${scoreInputs.length} score inputs`);
        
        scoreInputs.forEach(input => {
            input.addEventListener('input', function() {
                const studentId = this.dataset.studentId;
                console.log('📊 Score input changed for student:', studentId, 'Value:', this.value);
                updateGradeDisplay(studentId);
            });
            
            input.addEventListener('blur', function() {
                const studentId = this.dataset.studentId;
                console.log('📊 Score input blurred for student:', studentId);
                updateGradeDisplay(studentId);
            });
        });
    });

    function initializeGradeCalculations() {
        console.log('🔢 Initializing grade calculations for all students');
        document.querySelectorAll('.score-input').forEach(input => {
            const studentId = input.dataset.studentId;
            updateGradeDisplay(studentId);
        });
    }

    function updateGradeDisplay(studentId) {
        const scoreInput = document.querySelector(`.score-input[data-student-id="${studentId}"]`);
        if (!scoreInput) {
            console.error('❌ Score input not found for student:', studentId);
            return;
        }
        
        const warningElement = document.getElementById(`max-warning-${studentId}`);
        const score = parseFloat(scoreInput.value) || 0;
        
        console.log(`🔄 Updating display for student ${studentId}, score: ${score}`);
        
        // Visual feedback for invalid scores
        if (score > MAX_SCORE) {
            scoreInput.classList.add('invalid');
            if (warningElement) {
                warningElement.style.display = 'block';
            }
        } else {
            scoreInput.classList.remove('invalid');
            if (warningElement) {
                warningElement.style.display = 'none';
            }
        }
        
        // Calculate percentage
        const percentage = MAX_SCORE > 0 ? (score / MAX_SCORE) * 100 : 0;
        
        // Update percentage display
        const percentageElement = document.getElementById(`percentage-${studentId}`);
        if (percentageElement) {
            percentageElement.textContent = score > 0 ? percentage.toFixed(1) + '%' : '-';
            console.log(`📈 Updated percentage for ${studentId}: ${percentageElement.textContent}`);
        } else {
            console.error('❌ Percentage element not found for student:', studentId);
        }
        
        // Update grade letter
        updateGradeLetter(studentId, score, percentage);
    }

    function updateGradeLetter(studentId, score, percentage) {
        const gradeElement = document.getElementById(`grade-letter-${studentId}`);
        if (!gradeElement) {
            console.error('❌ Grade element not found for student:', studentId);
            return;
        }
        
        let gradeLetter = '-';
        let badgeClass = 'bg-gray-100 text-gray-800';
        
        if (score > 0) {
            if (percentage >= 90) {
                gradeLetter = 'A';
                badgeClass = 'bg-green-100 text-green-800';
            } else if (percentage >= 80) {
                gradeLetter = 'B';
                badgeClass = 'bg-blue-100 text-blue-800';
            } else if (percentage >= 70) {
                gradeLetter = 'C';
                badgeClass = 'bg-yellow-100 text-yellow-800';
            } else if (percentage >= 60) {
                gradeLetter = 'D';
                badgeClass = 'bg-orange-100 text-orange-800';
            } else {
                gradeLetter = 'F';
                badgeClass = 'bg-red-100 text-red-800';
            }
            
            gradeElement.innerHTML = 
                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">${gradeLetter}</span>`;
        } else {
            gradeElement.textContent = '-';
        }
        
        console.log(`🎓 Updated grade for ${studentId}: ${gradeLetter}`);
    }

    function saveGrade(studentId, callback = null) {
        console.log('💾 Starting save process for student:', studentId);
        
        const scoreInput = document.querySelector(`.score-input[data-student-id="${studentId}"]`);
        const commentsInput = document.querySelector(`.comments-input[data-student-id="${studentId}"]`);
        const saveButton = document.querySelector(`.save-single-grade[data-student-id="${studentId}"]`);
        
        if (!scoreInput || !commentsInput || !saveButton) {
            console.error('❌ Required elements not found for student:', studentId);
            showAlert('Error: Could not find required form elements', 'error');
            if (callback) callback(false);
            return;
        }
        
        const score = scoreInput.value ? parseFloat(scoreInput.value) : null;
        const comments = commentsInput.value;
        
        console.log('📦 Data to save:', { studentId, score, comments });
        console.log('🔗 Sending to:', GRADE_STORE_URL);
        
        // Validate score range
        if (score !== null && (score < 0 || score > MAX_SCORE)) {
            showAlert(`Score must be between 0 and ${MAX_SCORE}`, 'error');
            if (callback) callback(false);
            return;
        }

        // Show loading
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        saveButton.disabled = true;

        // Prepare data
        const formData = {
            student_id: studentId,
            score: score,
            comments: comments,
            _token: CSRF_TOKEN
        };

        console.log('📤 Making fetch request...');
        
        fetch(GRADE_STORE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            console.log('📨 Response received, status:', response.status);
            console.log('📨 Response headers:', Object.fromEntries(response.headers.entries()));
            
            if (!response.ok) {
                // Try to get error message from response
                return response.text().then(text => {
                    console.error('❌ Server response not OK:', text);
                    throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('✅ Server response data:', data);
            
            if (data && data.success) {
                // Update last updated time
                const now = new Date();
                const updatedElement = document.getElementById(`updated-${studentId}`);
                if (updatedElement) {
                    updatedElement.textContent = 
                        now.toLocaleString('en-US', { 
                            month: 'short', 
                            day: 'numeric', 
                            year: 'numeric',
                            hour: 'numeric', 
                            minute: '2-digit',
                            hour12: true 
                        });
                }
                
                // Remove invalid styling on successful save
                scoreInput.classList.remove('invalid');
                
                console.log('✅ Grade saved successfully for student:', studentId);
                
                if (!callback) {
                    showAlert('Grade saved successfully!', 'success');
                } else {
                    callback(true);
                }
            } else {
                const errorMsg = data && data.message ? data.message : 'Failed to save grade';
                console.error('❌ Server returned failure:', errorMsg);
                throw new Error(errorMsg);
            }
        })
        .catch(error => {
            console.error('❌ Fetch error:', error);
            console.error('❌ Error details:', {
                message: error.message,
                stack: error.stack
            });
            
            let userMessage = 'Error saving grade: ';
            if (error.message.includes('HTTP error! status: 404')) {
                userMessage += 'Route not found. Please check the server configuration.';
            } else if (error.message.includes('HTTP error! status: 500')) {
                userMessage += 'Server error. Please try again.';
            } else if (error.message.includes('Failed to fetch')) {
                userMessage += 'Network error. Please check your connection.';
            } else {
                userMessage += error.message;
            }
            
            showAlert(userMessage, 'error');
            if (callback) callback(false);
        })
        .finally(() => {
            // Restore button
            saveButton.innerHTML = '<i class="fas fa-save mr-1"></i> Save';
            saveButton.disabled = false;
            console.log('🔚 Save process completed for student:', studentId);
        });
    }

    async function saveAllGrades() {
        console.log('💾 Starting bulk save process...');
        
        const studentIds = Array.from(document.querySelectorAll('.score-input'))
            .map(input => input.dataset.studentId)
            .filter(id => id); // Remove any null/undefined IDs
        
        console.log(`📝 Found ${studentIds.length} students to save`);
        
        if (studentIds.length === 0) {
            showAlert('No students found to save grades for.', 'error');
            return;
        }

        const saveAllBtn = document.getElementById('saveAllBtn');
        const originalText = saveAllBtn.innerHTML;
        saveAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving All...';
        saveAllBtn.disabled = true;

        let successCount = 0;
        let errorCount = 0;

        // Save grades sequentially
        for (let i = 0; i < studentIds.length; i++) {
            const studentId = studentIds[i];
            console.log(`📝 Processing student ${i + 1}/${studentIds.length}: ${studentId}`);
            
            try {
                await new Promise((resolve) => {
                    saveGrade(studentId, (success) => {
                        if (success) {
                            successCount++;
                            console.log(`✅ Saved student ${studentId} (${successCount}/${studentIds.length})`);
                        } else {
                            errorCount++;
                            console.error(`❌ Failed to save student ${studentId}`);
                        }
                        resolve();
                    });
                });
                
                // Small delay between requests
                await new Promise(resolve => setTimeout(resolve, 100));
                
            } catch (error) {
                console.error(`❌ Error in bulk save for student ${studentId}:`, error);
                errorCount++;
            }
        }

        // Restore button
        saveAllBtn.innerHTML = originalText;
        saveAllBtn.disabled = false;

        // Show summary
        if (errorCount === 0 && successCount > 0) {
            showAlert(`All ${successCount} grades saved successfully!`, 'success');
        } else if (successCount > 0) {
            showAlert(`${successCount} grades saved successfully, ${errorCount} failed.`, 'warning');
        } else {
            showAlert('No grades were saved. Please check for errors.', 'error');
        }
        
        console.log('🔚 Bulk save completed:', { successCount, errorCount });
    }

    function showAlert(message, type) {
        // Remove existing alerts
        document.querySelectorAll('.custom-alert').forEach(alert => alert.remove());
        
        // Create alert
        const alertDiv = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
                    type === 'warning' ? 'bg-yellow-50 border-yellow-200 text-yellow-800' : 
                    'bg-red-50 border-red-200 text-red-800';
        
        alertDiv.className = `custom-alert fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg border ${bgColor}`;
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle'} mr-2"></i>
                <span class="font-medium">${message}</span>
                <button type="button" class="ml-4 ${type === 'success' ? 'text-green-500' : type === 'warning' ? 'text-yellow-500' : 'text-red-500'} hover:opacity-70" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Test function to check if everything is working
    function testSaveFunction() {
        console.log('🧪 Testing save function...');
        const firstStudent = document.querySelector('tr[id^="student-"]');
        if (!firstStudent) {
            console.error('❌ No students found for testing');
            return;
        }
        
        const studentId = firstStudent.id.replace('student-', '');
        console.log('🎯 Testing with student:', studentId);
        
        // Set test values
        const scoreInput = document.querySelector(`.score-input[data-student-id="${studentId}"]`);
        const commentsInput = document.querySelector(`.comments-input[data-student-id="${studentId}"]`);
        
        if (scoreInput && commentsInput) {
            scoreInput.value = '85';
            commentsInput.value = 'Test grade - please delete';
            
            // Update display
            updateGradeDisplay(studentId);
            
            // Save the grade
            saveGrade(studentId);
        } else {
            console.error('❌ Could not find input fields for testing');
        }
    }

    // Expose test function to console
    window.testSaveFunction = testSaveFunction;
    console.log('🔧 Test function available: testSaveFunction()');
</script>