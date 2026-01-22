<!-- Filters Section -->
<div class="bg-gray-50 rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Filter Students</h3>
    <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                   placeholder="Name, email, or admission number"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Grade Level Filter -->
        <div>
            <label for="grade_level_id" class="block text-sm font-medium text-gray-700 mb-1">Grade Level</label>
            <select name="grade_level_id" id="grade_level_id" 
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Grades</option>
                @foreach($gradeLevels as $grade)
                    <option value="{{ $grade->id }}" {{ request('grade_level_id') == $grade->id ? 'selected' : '' }}>
                        {{ $grade->name }} @if($grade->level) - Level {{ $grade->level }}@endif
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Class Group Filter -->
        <div>
            <label for="class_group_id" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
            <select name="class_group_id" id="class_group_id" 
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Classes</option>
                @foreach($classGroups as $class)
                    <option value="{{ $class->id }}" 
                            data-grade="{{ $class->grade_level_id }}"
                            {{ request('class_group_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} 
                        @if($class->section) - {{ $class->section }} @endif
                        @if($class->gradeLevel) ({{ $class->gradeLevel->name }}) @endif
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm">
                Apply Filters
            </button>
            <a href="{{ route('admin.students.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition text-sm">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Students Table -->
<div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
    @if($students->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Contact Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Class Groups</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($students as $student)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <!-- Student Info -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" 
                                         alt="{{ $student->user->name }}" 
                                         class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="bg-blue-500 text-white rounded-full h-10 w-10 flex items-center justify-center">
                                        {{ substr($student->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                <div class="text-sm text-gray-500">
                                    ID: {{ $student->admission_number ?? $student->id }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Contact Info -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $student->user->email }}</div>
                        <div class="text-sm text-gray-500">{{ $student->parent_phone }}</div>
                    </td>

                    <!-- Class Groups Info -->
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @if($student->classGroups->count() > 0)
                                @foreach($student->classGroups as $classGroup)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-1 mr-1">
                                        {{ $classGroup->name }}
                                        @if($classGroup->gradeLevel)
                                            <span class="text-blue-600">({{ $classGroup->gradeLevel->name }})</span>
                                        @endif
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400">No classes assigned</span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $student->classGroups->count() }} class(es)
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <!-- View Button -->
                            <a href="{{ route('admin.students.show', $student) }}" 
                               class="text-blue-600 hover:text-blue-900 transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a>

                            <!-- Edit Button -->
                            <a href="{{ route('admin.students.edit', $student) }}" 
                               class="text-green-600 hover:text-green-900 transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 transition duration-200 flex items-center"
                                        onclick="return confirm('Are you sure you want to delete this student?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete 
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">
            @if(request()->anyFilled(['search', 'grade_level_id', 'class_group_id']))
                No students match your current filters.
            @else
                No students found
            @endif
        </h3>
        <p class="mt-2 text-gray-500">
            @if(request()->anyFilled(['search', 'grade_level_id', 'class_group_id']))
                Try adjusting your filters or 
                <a href="{{ route('admin.students.index') }}" class="text-blue-600 hover:text-blue-500">clear all filters</a>.
            @else
                Get started by adding your first student.
            @endif
        </p>
        <div class="mt-6">
            <a href="{{ route('admin.students.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Student
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Summary -->
@if($students->count() > 0)
<div class="mt-6">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-700">
            Showing {{ $students->count() }} student(s)
        </p>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic class filtering based on grade level
    const gradeSelect = document.getElementById('grade_level_id');
    const classSelect = document.getElementById('class_group_id');
    
    if (gradeSelect && classSelect) {
        gradeSelect.addEventListener('change', function() {
            const gradeId = this.value;
            const options = classSelect.options;
            
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                const optionGrade = option.getAttribute('data-grade');
                
                if (!gradeId || optionGrade === gradeId || option.value === '') {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                    if (option.selected) {
                        option.selected = false;
                    }
                }
            }
        });
        
        // Trigger change on page load if grade is selected
        if (gradeSelect.value) {
            gradeSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>