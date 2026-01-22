@extends('layouts.app')

@section('content')
<div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Add New Teacher</h2>
                <p class="mt-2 text-sm text-gray-600">Create a new teacher account with department and subject assignments</p>
            </div>
            <a href="{{ route('admin.teachers.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Teachers
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center text-blue-600">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full">
                        <span class="text-sm font-medium text-white">1</span>
                    </div>
                    <span class="ml-2 text-sm font-medium">Account Info</span>
                </div>
                <div class="flex-1 h-1 mx-4 bg-blue-600"></div>
                <div class="flex items-center text-blue-600">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full">
                        <span class="text-sm font-medium text-white">2</span>
                    </div>
                    <span class="ml-2 text-sm font-medium">Profile</span>
                </div>
                <div class="flex-1 h-1 mx-4 bg-blue-600"></div>
                <div class="flex items-center text-blue-600">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full">
                        <span class="text-sm font-medium text-white">3</span>
                    </div>
                    <span class="ml-2 text-sm font-medium">Assignments</span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
            <div class="p-8 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('admin.teachers.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Account Information -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="ml-3 text-xl font-semibold text-gray-900">Account Information</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                                <input type="password" name="password" id="password" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Professional Details -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="ml-3 text-xl font-semibold text-gray-900">Professional Details</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Profile Image -->
                            <div class="sm:col-span-2">
                                <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                                <div class="flex items-center mt-1">
                                    <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <input type="file" name="profile_image" id="profile_image" 
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <p class="mt-1 text-xs text-gray-500">JPEG, PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                @error('profile_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="qualification" class="block text-sm font-medium text-gray-700">Highest Qualification *</label>
                                <input type="text" name="qualification" id="qualification" value="{{ old('qualification') }}" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('qualification') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                                <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}"
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('specialization') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="years_of_experience" class="block text-sm font-medium text-gray-700">Years of Experience *</label>
                                <input type="number" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience', 0) }}" min="0" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('years_of_experience') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="hire_date" class="block text-sm font-medium text-gray-700">Hire Date *</label>
                                <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('hire_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="office_location" class="block text-sm font-medium text-gray-700">Office Location</label>
                                <input type="text" name="office_location" id="office_location" value="{{ old('office_location') }}"
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('office_location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="office_hours" class="block text-sm font-medium text-gray-700">Office Hours</label>
                                <input type="text" name="office_hours" id="office_hours" value="{{ old('office_hours') }}" placeholder="e.g., Mon-Wed 2-4 PM"
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('office_hours') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                <textarea name="bio" id="bio" rows="4" placeholder="Tell us about the teacher's background, expertise, and interests..."
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('bio') }}</textarea>
                                @error('bio') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Department & Subject Assignments -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-full">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="ml-3 text-xl font-semibold text-gray-900">Department & Subject Assignments</h3>
                        </div>

                        <!-- Department Assignment -->
                        <!-- Department Assignment -->
                        <div class="sm:col-span-2">
                            <h3 class="mt-6 text-lg font-medium text-gray-900">Department Assignment</h3>
                            <p class="mt-1 text-sm text-gray-600">Assign teacher to departments. Select one as primary.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Departments *</label>
                            <div class="mt-2 space-y-2">
                                @foreach($departments as $department)
                                <div class="flex items-center">
                                    <input type="checkbox" name="departments[]" value="{{ $department->id }}" 
                                        id="department_{{ $department->id }}"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="department_{{ $department->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $department->name }}
                                    </label>
                                    <input type="radio" name="primary_department" value="{{ $department->id }}"
                                        id="primary_{{ $department->id }}"
                                        class="ml-4 w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="primary_{{ $department->id }}" class="ml-1 text-xs text-gray-500">
                                        Primary
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('departments') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            @error('primary_department') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Subject Assignment -->
                        <div class="mb-8">
                            <label class="block mb-4 text-lg font-medium text-gray-900">Subject Specialization</label>
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <p class="mb-4 text-sm text-gray-600">Select subjects this teacher is qualified to teach.</p>
                                
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($subjects as $subject)
                                    <div class="relative flex items-start p-3 bg-white border border-gray-200 rounded-lg hover:border-green-300 transition-colors">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                                id="subject_{{ $subject->id }}"
                                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="subject_{{ $subject->id }}" class="font-medium text-gray-700">
                                                {{ $subject->name }}
                                            </label>
                                            <p class="text-xs text-gray-500">{{ $subject->department->name }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Class Group Assignment -->
                        <div>
                            <label class="block mb-4 text-lg font-medium text-gray-900">Class Teacher Assignment</label>
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <p class="mb-4 text-sm text-gray-600">Assign as form teacher to classes (optional).</p>
                                
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($classGroups as $classGroup)
                                    <div class="relative flex items-start p-3 bg-white border border-gray-200 rounded-lg hover:border-orange-300 transition-colors">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="class_groups[]" value="{{ $classGroup->id }}" 
                                                id="class_{{ $classGroup->id }}"
                                                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="class_{{ $classGroup->id }}" class="font-medium text-gray-700">
                                                {{ $classGroup->name }}
                                            </label>
                                            <p class="text-xs text-gray-500">
                                                {{ $classGroup->gradeLevel->name }} 
                                                @if($classGroup->stream_type)
                                                • {{ ucfirst($classGroup->stream_type) }} Stream
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 mt-8 border-t border-gray-200">
                        <a href="{{ route('admin.teachers.index') }}" 
                           class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for better UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select primary department when department is checked
    const departmentCheckboxes = document.querySelectorAll('input[name="departments[]"]');
    const primaryRadios = document.querySelectorAll('input[name="primary_department"]');
    
    departmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const departmentId = this.value;
                const correspondingRadio = document.querySelector(`input[name="primary_department"][value="${departmentId}"]`);
                if (correspondingRadio && !document.querySelector('input[name="primary_department"]:checked')) {
                    correspondingRadio.checked = true;
                }
            }
        });
    });

    // Ensure primary department is always one of the checked departments
    primaryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const departmentId = this.value;
            const correspondingCheckbox = document.querySelector(`input[name="departments[]"][value="${departmentId}"]`);
            if (correspondingCheckbox && !correspondingCheckbox.checked) {
                correspondingCheckbox.checked = true;
            }
        });
    });
});
</script>
@endsection