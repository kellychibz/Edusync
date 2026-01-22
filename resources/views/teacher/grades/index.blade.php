@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Grade Management</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @if($classes->isEmpty())
                        <div class="col-span-full text-center py-12">
                            <div class="bg-gray-50 rounded-lg p-8">
                                <i class="fas fa-chalkboard-teacher fa-3x text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg">You are not assigned to any classes yet.</p>
                            </div>
                        </div>
                    @else
                        @foreach($classes as $class)
                            @php
                                $subject = $class->subjects->first();
                            @endphp
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $class->name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $class->students_count }} students
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-book mr-2 text-blue-500"></i>
                                            <span><strong>Subject:</strong> {{ $subject->name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                            <span><strong>Grade Level:</strong> {{ $class->gradeLevel->name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-stream mr-2 text-purple-500"></i>
                                            <span><strong>Stream:</strong> {{ $class->stream_type ?? 'General' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <a href="{{ route('teacher.grades.class', $class) }}" 
                                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fas fa-chart-line mr-2"></i>
                                            View Performance & Grades
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection