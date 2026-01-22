{{-- resources/views/admin/class-groups/manage-subjects.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Manage Subjects for {{ $classGroup->name }}</h2>
                        <p class="text-gray-600">{{ $classGroup->class_code }} • {{ $classGroup->gradeLevel->name ?? 'N/A' }}</p>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('admin.class-groups.show', $classGroup) }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                            Back to Class
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Current Stream Subjects -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold">Stream Subjects</h3>
                            <p class="text-sm text-gray-600 mt-1">Subjects defined for {{ $classGroup->stream->name ?? 'No Stream' }} stream</p>
                        </div>
                        <div class="p-6">
                            @if($classGroup->stream)
                                <div class="space-y-4">
                                    <!-- Core Subjects -->
                                    <div>
                                        <h4 class="font-medium text-blue-800 mb-3">Core Subjects</h4>
                                        <div class="space-y-2">
                                            @foreach($classGroup->stream->core_subjects_list as $subject)
                                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                                        <span class="font-medium">{{ $subject->name }}</span>
                                                        @if($subject->department)
                                                            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded">
                                                                {{ $subject->department->name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-sm text-blue-600">Required</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Optional Subjects -->
                                    @if($classGroup->stream->optional_subjects_list->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-green-800 mb-3">Optional Subjects</h4>
                                        <div class="space-y-2">
                                            @foreach($classGroup->stream->optional_subjects_list as $subject)
                                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                        <span class="font-medium">{{ $subject->name }}</span>
                                                        @if($subject->department)
                                                            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded">
                                                                {{ $subject->department->name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-sm text-green-600">Optional</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">No stream assigned to this class.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Manage Class Subjects -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold">Assign Subjects to Class</h3>
                            <p class="text-sm text-gray-600 mt-1">Select subjects and assign teachers</p>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('admin.class-groups.update-subjects', $classGroup) }}" method="POST">
                                @csrf
                                
                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    @foreach($allSubjects as $subject)
                                        @php
                                            $isAssigned = $classGroup->subjects->contains($subject->id);
                                            $currentAssignment = $classGroup->subjects->where('id', $subject->id)->first();
                                            $currentTeacherId = $currentAssignment ? $currentAssignment->pivot->teacher_id : null;
                                            $currentPeriods = $currentAssignment ? $currentAssignment->pivot->periods_per_week : 5;
                                        @endphp
                                        
                                        <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center space-x-3">
                                                    <input type="checkbox" 
                                                           name="subjects[{{ $subject->id }}][enabled]" 
                                                           value="1"
                                                           id="subject_{{ $subject->id }}"
                                                           {{ $isAssigned ? 'checked' : '' }}
                                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="subject_{{ $subject->id }}" class="font-medium text-gray-900">
                                                        {{ $subject->name }}
                                                    </label>
                                                </div>
                                                @if($classGroup->stream && in_array($subject->id, $classGroup->stream->core_subjects ?? []))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Core
                                                    </span>
                                                @elseif($classGroup->stream && in_array($subject->id, $classGroup->stream->optional_subjects ?? []))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Optional
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-7">
                                                <!-- Teacher Assignment -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                                                    <select name="subjects[{{ $subject->id }}][teacher_id]" 
                                                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                        <option value="">No Teacher</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" 
                                                                    {{ $currentTeacherId == $teacher->id ? 'selected' : '' }}>
                                                                {{ $teacher->user->name }}
                                                                @if($teacher->department)
                                                                    ({{ $teacher->department->name }})
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Periods per Week -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Periods/Week</label>
                                                    <input type="number" 
                                                           name="subjects[{{ $subject->id }}][periods_per_week]" 
                                                           value="{{ $currentPeriods }}"
                                                           min="1" max="10"
                                                           class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                                </div>
                                            </div>
                                            
                                            @if($subject->department)
                                                <div class="mt-2 ml-7">
                                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                        Department: {{ $subject->department->name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex items-center justify-between">
                                    <div>
                                        <button type="submit" 
                                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                                            Update Subjects
                                        </button>
                                        <a href="{{ route('admin.class-groups.show', $classGroup) }}" 
                                           class="ml-4 bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                                            Cancel
                                        </a>
                                    </div>
                                    
                                    <!-- Quick Actions -->
                                    <div class="space-x-2">
                                        <button type="button" id="select-all-core" 
                                                class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 transition">
                                            Select Core
                                        </button>
                                        <button type="button" id="deselect-all" 
                                                class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200 transition">
                                            Deselect All
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all core subjects
    document.getElementById('select-all-core').addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            const subjectItem = checkbox.closest('.border');
            const coreBadge = subjectItem.querySelector('.bg-blue-100');
            if (coreBadge && coreBadge.textContent.includes('Core')) {
                checkbox.checked = true;
            }
        });
    });

    // Deselect all subjects
    document.getElementById('deselect-all').addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Auto-enable core subjects when stream is changed (if implemented)
    const streamSelect = document.getElementById('stream_id');
    if (streamSelect) {
        streamSelect.addEventListener('change', function() {
            // This would need to be implemented based on your stream data structure
            console.log('Stream changed - would auto-select core subjects here');
        });
    }
});
</script>
@endsection