{{-- resources/views/admin/streams/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Edit Stream: {{ $stream->name }}</h2>
                    <a href="{{ route('admin.streams.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                        Back to Streams
                    </a>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.streams.update', $stream) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Stream Name</label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $stream->name) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       required>
                            </div>

                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Stream Code</label>
                                <input type="text" name="code" id="code" 
                                       value="{{ old('code', $stream->code) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                       required>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ old('description', $stream->description) }}</textarea>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       value="1" {{ $stream->is_active ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Active Stream
                                </label>
                            </div>
                        </div>

                        <!-- Subject Selection -->
                        <div class="space-y-4">
                            <!-- Core Subjects -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Core Subjects</label>
                                <div class="border border-gray-300 rounded-md p-4 max-h-60 overflow-y-auto">
                                    @foreach($subjects as $subject)
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="core_subjects[]" 
                                                   value="{{ $subject->id }}" 
                                                   id="core_subject_{{ $subject->id }}"
                                                   {{ in_array($subject->id, old('core_subjects', $stream->core_subjects ?? [])) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            <label for="core_subject_{{ $subject->id }}" 
                                                   class="ml-2 block text-sm text-gray-700">
                                                {{ $subject->name }}
                                                @if($subject->department)
                                                    <span class="text-xs text-gray-500">({{ $subject->department->name }})</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Optional Subjects -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Optional Subjects</label>
                                <div class="border border-gray-300 rounded-md p-4 max-h-60 overflow-y-auto">
                                    @foreach($subjects as $subject)
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="optional_subjects[]" 
                                                   value="{{ $subject->id }}" 
                                                   id="optional_subject_{{ $subject->id }}"
                                                   {{ in_array($subject->id, old('optional_subjects', $stream->optional_subjects ?? [])) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                            <label for="optional_subject_{{ $subject->id }}" 
                                                   class="ml-2 block text-sm text-gray-700">
                                                {{ $subject->name }}
                                                @if($subject->department)
                                                    <span class="text-xs text-gray-500">({{ $subject->department->name }})</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                            Update Stream
                        </button>
                        <a href="{{ route('admin.streams.index') }}" 
                           class="ml-4 bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection