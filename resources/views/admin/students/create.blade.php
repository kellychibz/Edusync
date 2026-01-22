@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Add New Student</h2>

                <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" id="studentForm">
                    @csrf
                    
                    @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <h4 class="font-bold">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Automatic Admission Number Preview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-blue-800 font-medium">Admission Number will be generated automatically</p>
                                <p class="text-blue-600 text-sm">Format: YEAR/SEQUENTIAL (e.g., {{ date('Y') }}/0001)</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Account Information</h3>
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                                <input type="password" name="password" id="password" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Photo Upload -->
                            <div class="mb-4">
                                <label for="photo" class="block text-sm font-medium text-gray-700">Student Photo</label>
                                <input type="file" name="photo" id="photo" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <p class="text-sm text-gray-500 mt-1">Accepted formats: jpeg, png, jpg, gif (max 2MB)</p>
                            </div>

                            <!-- Gender -->
                            <div class="mb-4">
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="gender" id="gender" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Student Details -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Student Details</h3>
                            
                            <div class="mb-4">
                                <label for="parent_phone" class="block text-sm font-medium text-gray-700">Parent Phone *</label>
                                <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Enhanced Class Groups Selection -->
                            <div class="mb-4">
                                <label for="class_group_ids" class="block text-sm font-medium text-gray-700">Class Assignment</label>
                                
                                <!-- Grade Level Filter -->
                                <div class="mb-3">
                                    <label for="grade_filter" class="block text-xs font-medium text-gray-500 mb-1">Filter by Grade Level</label>
                                    <select id="grade_filter" class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option value="">All Grades</option>
                                        @foreach($gradeLevels as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Class Selection with Capacity Info -->
                                <select name="class_group_ids[]" id="class_group_ids" multiple
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 class-selector"
                                    style="height: 120px;">
                                    @foreach($classGroups as $classGroup)
                                        @php
                                            $currentStudents = $classGroup->students_count ?? 0;
                                            $capacity = $classGroup->capacity ?? 0;
                                            $isFull = $capacity > 0 && $currentStudents >= $capacity;
                                            $availableSpots = $capacity - $currentStudents;
                                        @endphp
                                        <option value="{{ $classGroup->id }}" 
                                                data-grade="{{ $classGroup->grade_level_id }}"
                                                data-capacity="{{ $capacity }}"
                                                data-current="{{ $currentStudents }}"
                                                data-available="{{ $availableSpots }}"
                                                {{ $isFull ? 'disabled' : '' }}
                                                {{ in_array($classGroup->id, old('class_group_ids', [])) ? 'selected' : '' }}>
                                            {{ $classGroup->name }} 
                                            @if($classGroup->section) - {{ $classGroup->section }} @endif
                                            ({{ $classGroup->gradeLevel->name ?? 'N/A' }})
                                            @if($capacity > 0)
                                                - {{ $currentStudents }}/{{ $capacity }} students
                                                @if($isFull)
                                                    - ❌ FULL
                                                @else
                                                    - ✅ {{ $availableSpots }} spots left
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple classes. Full classes are disabled.</p>
                                
                                <!-- Capacity Summary -->
                                <div id="capacitySummary" class="mt-2 p-2 bg-gray-50 rounded text-sm hidden">
                                    <div id="selectedCount" class="text-blue-600"></div>
                                    <div id="capacityWarnings" class="text-red-600 mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rest of your form (Address, Emergency Contact, Medical Info) remains the same -->
                    <!-- ... [Keep all your existing form sections] ... -->

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                            Create Student
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="ml-4 text-gray-600 hover:text-gray-900">
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
    const gradeFilter = document.getElementById('grade_filter');
    const classSelector = document.getElementById('class_group_ids');
    const capacitySummary = document.getElementById('capacitySummary');
    const selectedCount = document.getElementById('selectedCount');
    const capacityWarnings = document.getElementById('capacityWarnings');

    // Grade level filtering
    gradeFilter.addEventListener('change', function() {
        const selectedGrade = this.value;
        const options = classSelector.querySelectorAll('option:not([value=""])');
        
        options.forEach(option => {
            if (!selectedGrade || option.getAttribute('data-grade') === selectedGrade) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // Capacity summary and validation
    classSelector.addEventListener('change', function() {
        const selectedOptions = Array.from(this.selectedOptions);
        const fullClassesSelected = selectedOptions.filter(opt => opt.disabled).length;
        
        if (selectedOptions.length > 0) {
            capacitySummary.classList.remove('hidden');
            selectedCount.textContent = `Selected: ${selectedOptions.length} class(es)`;
            
            if (fullClassesSelected > 0) {
                capacityWarnings.innerHTML = `⚠️ ${fullClassesSelected} full class(es) selected. These will be ignored.`;
            } else {
                capacityWarnings.innerHTML = '';
            }
        } else {
            capacitySummary.classList.add('hidden');
        }
    });

    // Form validation to prevent submitting full classes
    document.getElementById('studentForm').addEventListener('submit', function(e) {
        const selectedOptions = Array.from(classSelector.selectedOptions);
        const fullClassesSelected = selectedOptions.filter(opt => opt.disabled);
        
        if (fullClassesSelected.length > 0) {
            e.preventDefault();
            alert('Please remove full classes from your selection before submitting.');
        }
    });
});
</script>

<style>
select[multiple] {
    background-image: none;
}

option:disabled {
    background-color: #fef2f2;
    color: #dc2626;
}

.class-selector option {
    padding: 8px;
    border-bottom: 1px solid #e5e7eb;
}
</style>
@endsection