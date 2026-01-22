@extends('layouts.app')

@section('title', 'My Assessments')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Assessments</h2>
                    <div class="text-sm text-gray-600">
                        Academic Year: <span class="font-semibold">{{ $currentAcademicYear->year }}</span>
                    </div>
                </div>

                @if($classes->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($classes as $classGroup)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $classGroup->name }}</h3>
                                        <div class="flex items-center space-x-2">
                                            @if($classGroup->gradeLevel)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                    {{ $classGroup->gradeLevel->name }}
                                                </span>
                                            @endif
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                                {{ $classGroup->students_count }} students
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">Your Subjects:</p>
                                        <div class="space-y-2">
                                            @foreach($classGroup->subjects as $subject)
                                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                                    <div class="flex-1">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ $subject->name }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 ml-2">
                                                            {{ $subject->code }}
                                                        </span>
                                                    </div>
                                                    <div class="flex space-x-1">
                                                        <a href="{{ route('teacher.assessments.config.create', ['classGroup' => $classGroup->id, 'subject' => $subject->id]) }}" 
                                                           class="inline-flex items-center px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded hover:bg-indigo-200 transition-colors"
                                                           title="Configure Assessment">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            Config
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('teacher.assessments.class', $classGroup->id) }}" 
                                           class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Manage Assessments
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No classes assigned</h3>
                        <p class="mt-2 text-gray-600">You are not assigned to any classes for the current academic year.</p>
                        <p class="mt-1 text-sm text-gray-500">Contact administration to get assigned to classes.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection