@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Class Allocation Management</h2>
                        <p class="text-gray-600 mt-1">Manage student class assignments and capacity</p>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('admin.class-allocations.create') }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Bulk Assign Students
                        </a>
                        <a href="{{ route('admin.class-groups.index') }}" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            Manage Classes
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Overview Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Total Students</p>
                                <p class="text-2xl font-semibold text-blue-900">{{ \App\Models\Student::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Assigned Students</p>
                                <p class="text-2xl font-semibold text-green-900">{{ \App\Models\Student::has('classGroups')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-orange-600">Unassigned Students</p>
                                <p class="text-2xl font-semibold text-orange-900">{{ $unassignedStudents->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600">Active Classes</p>
                                <p class="text-2xl font-semibold text-purple-900">{{ \App\Models\ClassGroup::where('is_active', true)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unassigned Students Alert -->
                @if($unassignedStudents->count() > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <p class="text-yellow-800 font-medium">
                                {{ $unassignedStudents->count() }} students are not assigned to any class
                            </p>
                            <p class="text-yellow-700 text-sm mt-1">
                                <a href="{{ route('admin.class-allocations.create') }}" class="underline hover:text-yellow-900">
                                    Assign them to classes now
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Classes by Grade Level -->
                <div class="space-y-6">
                    @foreach($gradeLevels as $gradeLevel)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                {{ $gradeLevel->name }}
                                @if($gradeLevel->level)
                                    <span class="ml-2 text-sm text-gray-600 font-normal">(Level {{ $gradeLevel->level }})</span>
                                @endif
                                <span class="ml-auto text-sm text-gray-500">
                                    {{ $gradeLevel->classes->count() }} classes
                                </span>
                            </h3>
                        </div>

                        <div class="bg-white">
                            @if($gradeLevel->classes->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                                    @foreach($gradeLevel->classes as $class)
                                    @php
                                        $studentCount = $class->students->count();
                                        $capacity = $class->capacity ?? 0;
                                        $percentage = $capacity > 0 ? ($studentCount / $capacity) * 100 : 0;
                                        $isFull = $capacity > 0 && $studentCount >= $capacity;
                                        $isNearFull = $capacity > 0 && $studentCount >= $capacity * 0.8;
                                    @endphp
                                    
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $class->name }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    @if($class->section){{ $class->section }} • @endif
                                                    @if($class->teacher)
                                                        {{ $class->teacher->user->name }}
                                                    @else
                                                        No teacher assigned
                                                    @endif
                                                </p>
                                            </div>
                                            <a href="{{ route('admin.class-allocations.show', $class) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Manage
                                            </a>
                                        </div>

                                        <!-- Capacity Bar -->
                                        @if($capacity > 0)
                                        <div class="mb-3">
                                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                                <span>Capacity</span>
                                                <span>{{ $studentCount }}/{{ $capacity }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full {{ $isFull ? 'bg-red-500' : ($isNearFull ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                                     style="width: {{ min($percentage, 100) }}%"></div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600">{{ $studentCount }} students</span>
                                            @if($isFull)
                                                <span class="text-red-600 font-medium">Full</span>
                                            @elseif($isNearFull)
                                                <span class="text-yellow-600 font-medium">Almost Full</span>
                                            @else
                                                <span class="text-green-600 font-medium">Available</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-6 text-center text-gray-500">
                                    No classes found for this grade level
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection