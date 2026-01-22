@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Student Details</h2>
                        <p class="text-gray-600">Complete student information</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.students.edit', $student) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                            Edit Student
                        </a>
                        <a href="{{ route('admin.students.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                            Back to List
                        </a>
                    </div>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Personal Info -->
                    <div class="lg:col-span-1">
                        <!-- Student Photo -->
                        <div class="text-center mb-6">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" 
                                     alt="{{ $student->user->name }}" 
                                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-300">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center border-4 border-gray-300">
                                    <span class="text-4xl text-gray-400">{{ substr($student->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <h3 class="text-xl font-semibold mt-4">{{ $student->user->name }}</h3>
                            <p class="text-gray-600">{{ $student->user->email }}</p>
                        </div>

                        <!-- Basic Information -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800">Basic Information</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Student ID</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->gender ? ucfirst($student->gender) : 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->date_of_birth->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Age</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->date_of_birth->age }} years old</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Parent Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->parent_phone }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Class Groups -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800">Class Groups</h4>
                            @if($student->classGroups->count() > 0)
                                <div class="space-y-2">
                                    @foreach($student->classGroups as $classGroup)
                                    <div class="bg-white rounded-lg border border-gray-200 p-3">
                                        <div class="font-medium text-gray-900">{{ $classGroup->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            @if($classGroup->section)Section: {{ $classGroup->section }} • @endif
                                            @if($classGroup->subject){{ $classGroup->subject }} • @endif
                                            Room: {{ $classGroup->room_number ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Teacher: {{ $classGroup->teacher->user->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No class groups assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Additional Info -->
                    <div class="lg:col-span-2">
                        <!-- Address Information -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800">Address Information</h4>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->address ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">City</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->city ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">State</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->state ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ZIP Code</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->zip_code ?? 'Not specified' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->country ?? 'Not specified' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800">Emergency Contact</h4>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Contact Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->emergency_contact_name ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Contact Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->emergency_contact_phone ?? 'Not specified' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Relationship</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->emergency_contact_relationship ?? 'Not specified' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Medical Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800">Medical Information</h4>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Blood Type</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->blood_type ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Insurance Provider</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->insurance_provider ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Policy Number</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->insurance_policy_number ?? 'Not specified' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Medical Conditions</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($student->medical_conditions && is_array($student->medical_conditions))
                                            {{ implode(', ', $student->medical_conditions) }}
                                        @else
                                            None specified
                                        @endif
                                    </dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Allergies</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($student->allergies && is_array($student->allergies))
                                            {{ implode(', ', $student->allergies) }}
                                        @else
                                            None specified
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons at Bottom -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                    <div>
                        <span class="text-sm text-gray-500">
                            Created: {{ $student->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                                Delete Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection