<div class="mb-6">
    <h3 class="text-xl font-semibold text-gray-800 mb-2">Bulk Class Assignment</h3>
    <p class="text-gray-600">Assign multiple students to classes in one operation</p>
</div>

@php
    // Get the data needed for bulk assignment
    $allStudents = \App\Models\Student::with(['user', 'classGroups'])->get();
    $classGroupsGrouped = \App\Models\ClassGroup::with(['gradeLevel', 'students'])
        ->where('is_active', true)
        ->get()
        ->groupBy('grade_level_id');
    $gradeLevels = \App\Models\GradeLevel::where('is_active', true)->get();
@endphp

<!-- Bulk Assignment Form -->
<form action="{{ route('admin.class-allocations.store') }}" method="POST" id="bulkAssignmentForm">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column - Student Selection -->
        <div>
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Select Students</h3>
            
            <!-- Student Search -->
            <div class="mb-4">
                <label for="studentSearch" class="block text-sm font-medium text-gray-700 mb-1">Search Students</label>
                <input type="text" id="studentSearch" placeholder="Search by name or admission number..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Student List -->
            <div class="border border-gray-300 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Students</span>
                        <button type="button" id="selectAllStudents" class="text-blue-600 hover:text-blue-800 text-sm">
                            Select All
                        </button>
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto p-4 space-y-2">
                    @foreach($allStudents as $student)
                    <div class="student-item flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                               id="student_{{ $student->id }}"
                               class="student-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="student_{{ $student->id }}" class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $student->user->name }}</span>
                                    <div class="text-xs text-gray-500">
                                        {{ $student->admission_number ?? $student->id }} • {{ $student->user->email }}
                                    </div>
                                </div>
                                <div class="text-xs text-gray-400">
                                    @if($student->classGroups->count() > 0)
                                        {{ $student->classGroups->count() }} class(es)
                                    @else
                                        <span class="text-orange-500">Unassigned</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Selected Count -->
            <div id="selectedStudentsCount" class="mt-3 text-sm text-blue-600 hidden">
                <span id="count">0</span> students selected
            </div>
        </div>

        <!-- Right Column - Class Selection & Operations -->
        <div>
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Class Assignment</h3>
            
            <!-- Operation Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Operation Type</label>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="radio" name="operation" value="assign" id="op_assign" checked
                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="op_assign" class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Assign to Classes</span>
                            <p class="text-gray-500 text-xs">Add students to selected classes (keep existing classes)</p>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="operation" value="transfer" id="op_transfer"
                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="op_transfer" class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Transfer to Classes</span>
                            <p class="text-gray-500 text-xs">Replace all current classes with selected classes</p>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="operation" value="remove" id="op_remove"
                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="op_remove" class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Remove from Classes</span>
                            <p class="text-gray-500 text-xs">Remove students from selected classes</p>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Grade Level Filter -->
            <div class="mb-4">
                <label for="gradeFilter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Grade Level</label>
                <select id="gradeFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Grades</option>
                    @foreach($gradeLevels as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Classes</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden max-h-64 overflow-y-auto">
                    @foreach($classGroupsGrouped as $gradeId => $classes)
                    <div class="class-group" data-grade="{{ $gradeId }}">
                        @if($gradeId)
                            @php $grade = $gradeLevels->find($gradeId); @endphp
                            <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                                <span class="text-sm font-medium text-gray-700">{{ $grade->name ?? 'Unknown Grade' }}</span>
                            </div>
                        @endif
                        <div class="p-3 space-y-2">
                            @foreach($classes as $class)
                            @php
                                $studentCount = $class->students->count();
                                $capacity = $class->capacity ?? 0;
                                $isFull = $capacity > 0 && $studentCount >= $capacity;
                            @endphp
                            <div class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50">
                                <input type="checkbox" name="class_group_ids[]" value="{{ $class->id }}" 
                                       id="class_{{ $class->id }}"
                                       {{ $isFull ? 'disabled' : '' }}
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="class_{{ $class->id }}" class="ml-2 flex-1">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium {{ $isFull ? 'text-gray-400' : 'text-gray-900' }}">
                                            {{ $class->name }}
                                            @if($class->section) - {{ $class->section }} @endif
                                        </span>
                                        <span class="text-xs {{ $isFull ? 'text-red-500' : 'text-gray-500' }}">
                                            {{ $studentCount }}/{{ $capacity > 0 ? $capacity : '∞' }}
                                            @if($isFull) • FULL @endif
                                        </span>
                                    </div>
                                    @if($class->teacher)
                                    <div class="text-xs text-gray-500">
                                        Teacher: {{ $class->teacher->user->name }}
                                    </div>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600" id="operationSummary">
                            No students selected
                        </p>
                    </div>
                    <button type="submit" id="submitButton" disabled
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Execute Assignment
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSearch = document.getElementById('studentSearch');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    const selectAllBtn = document.getElementById('selectAllStudents');
    const selectedCountDiv = document.getElementById('selectedStudentsCount');
    const countSpan = document.getElementById('count');
    const operationSummary = document.getElementById('operationSummary');
    const submitButton = document.getElementById('submitButton');
    const gradeFilter = document.getElementById('gradeFilter');
    const classGroups = document.querySelectorAll('.class-group');
    const operationRadios = document.querySelectorAll('input[name="operation"]');

    // Student Search
    studentSearch.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        studentCheckboxes.forEach(checkbox => {
            const label = checkbox.closest('.student-item');
            const text = label.textContent.toLowerCase();
            label.style.display = text.includes(searchTerm) ? 'flex' : 'none';
        });
    });

    // Select All Students
    selectAllBtn.addEventListener('click', function() {
        const allSelected = Array.from(studentCheckboxes).every(cb => cb.checked);
        studentCheckboxes.forEach(checkbox => {
            checkbox.checked = !allSelected;
        });
        updateSelectionCount();
    });

    // Update selection count
    function updateSelectionCount() {
        const selectedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
        countSpan.textContent = selectedCount;
        
        if (selectedCount > 0) {
            selectedCountDiv.classList.remove('hidden');
            submitButton.disabled = false;
            updateOperationSummary(selectedCount);
        } else {
            selectedCountDiv.classList.add('hidden');
            submitButton.disabled = true;
            operationSummary.textContent = 'No students selected';
        }
    }

    // Update operation summary
    function updateOperationSummary(selectedCount) {
        const operation = document.querySelector('input[name="operation"]:checked').value;
        const operations = {
            'assign': `Assign ${selectedCount} student(s) to selected classes`,
            'transfer': `Transfer ${selectedCount} student(s) to selected classes`,
            'remove': `Remove ${selectedCount} student(s) from selected classes`
        };
        operationSummary.textContent = operations[operation];
    }

    // Grade level filtering for classes
    gradeFilter.addEventListener('change', function() {
        const selectedGrade = this.value;
        classGroups.forEach(group => {
            if (!selectedGrade || group.getAttribute('data-grade') === selectedGrade) {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
            }
        });
    });

    // Operation type change
    operationRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
            if (selectedCount > 0) {
                updateOperationSummary(selectedCount);
            }
        });
    });

    // Initialize selection count
    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });

    // Form validation
    document.getElementById('bulkAssignmentForm').addEventListener('submit', function(e) {
        const selectedStudents = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
        const selectedClasses = Array.from(document.querySelectorAll('input[name="class_group_ids[]"]:checked:not(:disabled)')).length;
        const operation = document.querySelector('input[name="operation"]:checked').value;

        if (selectedStudents === 0) {
            e.preventDefault();
            alert('Please select at least one student.');
            return;
        }

        if (operation !== 'remove' && selectedClasses === 0) {
            e.preventDefault();
            alert('Please select at least one class.');
            return;
        }

        if (!confirm(`Are you sure you want to ${operation} ${selectedStudents} student(s)?`)) {
            e.preventDefault();
        }
    });
});
</script>