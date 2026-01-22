{{-- resources/views/teacher/attendance/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Mark Attendance</h2>
                        <p class="text-gray-600 mt-1">
                            {{ $classSubject->classGroup->name }} - {{ $classSubject->subject->name }}
                        </p>
                        <p class="text-sm text-gray-500">Date: {{ $date }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $students->count() }} Students
                    </span>
                </div>

                <form action="{{ route('teacher.attendance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $classSubject->class_id }}">
                    <input type="hidden" name="subject_id" value="{{ $classSubject->subject_id }}">
                    <input type="hidden" name="attendance_date" value="{{ $date }}">
                    
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Student Attendance</h3>
                            <p class="text-sm text-gray-600 mt-1">Select attendance status for each student</p>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No.</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Excused</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $index => $student)
                                            @php
                                                $existingRecord = $existingAttendance[$student->id] ?? null;
                                                $defaultType = $existingRecord ? $existingRecord->attendance_type_id : 1; // Default to Present
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $student->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $student->admission_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <select name="attendance[{{ $index }}][attendance_type_id]" 
                                                            class="attendance-status rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                        @foreach($attendanceTypes as $type)
                                                            <option value="{{ $type->id }}" 
                                                                    {{ $defaultType == $type->id ? 'selected' : '' }}
                                                                    data-color="{{ $type->color }}">
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="text" 
                                                           name="attendance[{{ $index }}][notes]" 
                                                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm w-full"
                                                           placeholder="Optional notes"
                                                           value="{{ $existingRecord->notes ?? '' }}">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" 
                                                           name="attendance[{{ $index }}][is_excused]" 
                                                           value="1"
                                                           {{ $existingRecord && $existingRecord->is_excused ? 'checked' : '' }}
                                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                            <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Dashboard
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Attendance
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.attendance-status');
    
    statusSelects.forEach(select => {
        // Set initial color
        const selectedOption = select.options[select.selectedIndex];
        const color = selectedOption.getAttribute('data-color');
        select.style.borderLeft = `4px solid ${color}`;
        select.style.paddingLeft = '8px';
        
        // Update color on change
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const color = selectedOption.getAttribute('data-color');
            this.style.borderLeftColor = color;
        });
    });
});
</script>
@endsection