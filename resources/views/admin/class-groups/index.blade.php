{{-- resources/views/admin/class-groups/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Class Management</h2>
                    <a href="{{ route('admin.class-groups.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Create New Class
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Filter Classes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="grade_filter" class="block text-sm font-medium text-gray-700">Grade Level</label>
                            <select id="grade_filter" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="">All Grades</option>
                                @foreach($gradeLevels as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="stream_filter" class="block text-sm font-medium text-gray-700">Stream</label>
                            <select id="stream_filter" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="">All Streams</option>
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button id="reset_filters" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Class Details
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade & Stream
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Students
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Form Teacher
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($classGroups as $classGroup)
                                <tr class="class-row" 
                                    data-grade="{{ $classGroup->grade_level_id }}"
                                    data-stream="{{ $classGroup->stream_id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $classGroup->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $classGroup->class_code }}</div>
                                        @if($classGroup->description)
                                            <div class="text-sm text-gray-400 mt-1">{{ $classGroup->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">{{ $classGroup->gradeLevel->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            @if($classGroup->stream)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $classGroup->stream->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">No stream</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $classGroup->students_count }} students</div>
                                        @if($classGroup->capacity)
                                            <div class="text-sm text-gray-500">
                                                Capacity: {{ $classGroup->capacity }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($classGroup->teacher)
                                            {{ $classGroup->teacher->user->name }}
                                        @else
                                            <span class="text-gray-400">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.class-groups.show', $classGroup) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('admin.class-groups.edit', $classGroup) }}" 
                                           class="text-green-600 hover:text-green-900">Edit</a>
                                        <a href="{{ route('admin.class-groups.manage-subjects', $classGroup) }}" 
                                           class="text-purple-600 hover:text-purple-900">Subjects</a>
                                        <form action="{{ route('admin.class-groups.destroy', $classGroup) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this class?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($classGroups->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="mt-4 text-gray-500">No classes found.</p>
                        <a href="{{ route('admin.class-groups.create') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-500">
                            Create your first class →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeFilter = document.getElementById('grade_filter');
    const streamFilter = document.getElementById('stream_filter');
    const resetButton = document.getElementById('reset_filters');
    const classRows = document.querySelectorAll('.class-row');

    function filterClasses() {
        const selectedGrade = gradeFilter.value;
        const selectedStream = streamFilter.value;

        classRows.forEach(row => {
            const rowGrade = row.getAttribute('data-grade');
            const rowStream = row.getAttribute('data-stream');

            const gradeMatch = !selectedGrade || rowGrade === selectedGrade;
            const streamMatch = !selectedStream || rowStream === selectedStream;

            if (gradeMatch && streamMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    gradeFilter.addEventListener('change', filterClasses);
    streamFilter.addEventListener('change', filterClasses);

    resetButton.addEventListener('click', function() {
        gradeFilter.value = '';
        streamFilter.value = '';
        filterClasses();
    });
});
</script>
@endsection