@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Edit Student: {{ $student->user->name }}</h2>

                <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Account Information</h3>
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $student->user->name) }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $student->user->email) }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Photo Upload -->
                            <div class="mb-4">
                                <label for="photo" class="block text-sm font-medium text-gray-700">Student Photo</label>
                                @if($student->photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $student->photo) }}" 
                                         alt="Current photo" 
                                         class="w-20 h-20 rounded object-cover border">
                                    <p class="text-sm text-gray-500 mt-1">Current photo</p>
                                </div>
                                @endif
                                <input type="file" name="photo" id="photo" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <p class="text-sm text-gray-500 mt-1">Accepted formats: jpeg, png, jpg, gif (max 2MB)</p>
                            </div>

                            <!-- Gender -->
                            <div class="mb-4">
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="gender" id="gender" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Student Details -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Student Details</h3>
                            
                            <div class="mb-4">
                                <label for="parent_phone" class="block text-sm font-medium text-gray-700">Parent Phone</label>
                                <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Class Groups Selection -->
                            <div class="mb-4">
                                <label for="class_group_ids" class="block text-sm font-medium text-gray-700">Class Groups</label>
                                <select name="class_group_ids[]" id="class_group_ids" multiple
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    style="height: 120px;">
                                    <option value="">Select Class Groups</option>
                                    @foreach($classGroups as $classGroup)
                                        <option value="{{ $classGroup->id }}" 
                                            {{ in_array($classGroup->id, old('class_group_ids', $student->classGroups->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $classGroup->name }} 
                                            @if($classGroup->section) - {{ $classGroup->section }} @endif
                                            @if($classGroup->subject) ({{ $classGroup->subject }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple classes</p>
                                @if($student->classGroups->count() > 0)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Currently assigned to {{ $student->classGroups->count() }} class(es)</p>
                                </div>
                                @endif
                            </div>

                            <!-- Address Information -->
                            <h3 class="text-lg font-semibold mt-6 mb-4">Address Information</h3>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $student->address) }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $student->city) }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" name="state" id="state" value="{{ old('state', $student->state) }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $student->zip_code) }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <input type="text" name="country" id="country" value="{{ old('country', $student->country) }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <h3 class="text-lg font-semibold mt-6 mb-4">Emergency Contact</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Contact Name</label>
                            <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                                value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                            <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" 
                                value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        
                        <div>
                            <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700">Relationship</label>
                            <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" 
                                value="{{ old('emergency_contact_relationship', $student->emergency_contact_relationship) }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <h3 class="text-lg font-semibold mt-6 mb-4">Medical Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="medical_conditions" class="block text-sm font-medium text-gray-700">Medical Conditions (comma-separated)</label>
                            <textarea name="medical_conditions" id="medical_conditions" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Asthma, Diabetes, etc.">{{ old('medical_conditions', is_array($student->medical_conditions) ? implode(', ', $student->medical_conditions) : $student->medical_conditions) }}</textarea>
                        </div>
                        
                        <div>
                            <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies (comma-separated)</label>
                            <textarea name="allergies" id="allergies" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
                                placeholder="Peanuts, Penicillin, etc.">{{ old('allergies', is_array($student->allergies) ? implode(', ', $student->allergies) : $student->allergies) }}</textarea>
                        </div>
                        
                        <div>
                            <label for="blood_type" class="block text-sm font-medium text-gray-700">Blood Type</label>
                            <select name="blood_type" id="blood_type" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Select Blood Type</option>
                                <option value="A+" {{ old('blood_type', $student->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_type', $student->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_type', $student->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_type', $student->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_type', $student->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_type', $student->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_type', $student->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_type', $student->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="insurance_provider" class="block text-sm font-medium text-gray-700">Insurance Provider</label>
                            <input type="text" name="insurance_provider" id="insurance_provider" 
                                value="{{ old('insurance_provider', $student->insurance_provider) }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        
                        <div>
                            <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700">Policy Number</label>
                            <input type="text" name="insurance_policy_number" id="insurance_policy_number" 
                                value="{{ old('insurance_policy_number', $student->insurance_policy_number) }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <div>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                                Update Student
                            </button>
                            <a href="{{ route('admin.students.show', $student) }}" class="ml-4 text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                        
                        <div>
                            <a href="{{ route('admin.students.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                                Back to List
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
select[multiple] {
    background-image: none;
}
</style>
@endsection