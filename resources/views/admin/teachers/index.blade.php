@extends('layouts.app')

@section('content')
<div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Teacher Management</h2>
            <a href="{{ route('admin.teachers.create') }}"
                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                + Add New Teacher
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Teachers Table -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Qualification
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Specialization
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Experience
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Hire Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($teachers as $teacher)
                        <tr>
                            <!-- Replace the current avatar cell with this -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        @if($teacher->profile_image)
                                            <img src="{{ $teacher->profile_image_url }}" alt="{{ $teacher->user->name }}" 
                                                class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                                <span class="font-bold text-blue-800">{{ substr($teacher->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $teacher->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $teacher->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $teacher->qualification }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $teacher->specialization ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $teacher->years_of_experience }} yrs</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $teacher->hire_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="ml-3 text-yellow-600 hover:text-yellow-900">Edit</a>
                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline-block ml-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this teacher?')" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                No teachers found. <a href="{{ route('admin.teachers.create') }}" class="text-blue-500 hover:text-blue-700">Add one now.</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection