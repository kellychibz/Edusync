{{-- resources/views/admin/streams/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Stream Details: {{ $stream->name }}</h2>
                    <div class="space-x-2">
                        <a href="{{ route('admin.streams.edit', $stream) }}" 
                           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                            Edit Stream
                        </a>
                        <a href="{{ route('admin.streams.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                            Back to Streams
                        </a>
                    </div>
                </div>

                <!-- Stream Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Stream Name</dt>
                                <dd class="text-sm text-gray-900">{{ $stream->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Stream Code</dt>
                                <dd class="text-sm text-gray-900">{{ $stream->code }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="text-sm text-gray-900">{{ $stream->description ?? 'No description' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $stream->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $stream->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Statistics</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Classes</dt>
                                <dd class="text-sm text-gray-900">{{ $stream->classGroups->count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Core Subjects</dt>
                                <dd class="text-sm text-gray-900">{{ count($stream->core_subjects ?? []) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Optional Subjects</dt>
                                <dd class="text-sm text-gray-900">{{ count($stream->optional_subjects ?? []) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Subjects -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Core Subjects -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-800">Core Subjects</h3>
                        @if($stream->core_subjects_list->count() > 0)
                            <div class="space-y-2">
                                @foreach($stream->core_subjects_list as $subject)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                        <span class="font-medium">{{ $subject->name }}</span>
                                        @if($subject->department)
                                            <span class="text-sm text-gray-500 bg-blue-100 px-2 py-1 rounded">
                                                {{ $subject->department->name }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No core subjects defined.</p>
                        @endif
                    </div>

                    <!-- Optional Subjects -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-green-800">Optional Subjects</h3>
                        @if($stream->optional_subjects_list->count() > 0)
                            <div class="space-y-2">
                                @foreach($stream->optional_subjects_list as $subject)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                        <span class="font-medium">{{ $subject->name }}</span>
                                        @if($subject->department)
                                            <span class="text-sm text-gray-500 bg-green-100 px-2 py-1 rounded">
                                                {{ $subject->department->name }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No optional subjects defined.</p>
                        @endif
                    </div>
                </div>

                <!-- Associated Classes -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Associated Classes</h3>
                    </div>
                    <div class="p-6">
                        @if($stream->classGroups->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade Level</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Students</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Form Teacher</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stream->classGroups as $classGroup)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="{{ route('admin.class-groups.show', $classGroup) }}" 
                                                       class="text-blue-600 hover:text-blue-900 font-medium">
                                                        {{ $classGroup->name }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $classGroup->gradeLevel->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $classGroup->students->count() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $classGroup->form_teacher }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No classes are associated with this stream.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection