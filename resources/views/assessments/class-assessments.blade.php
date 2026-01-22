@extends('layouts.app')

@section('title', 'Class Assessments - ' . $classGroup->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Assessments - {{ $classGroup->name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $classGroup->gradeLevel->name ?? '' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.assessments.class.final-report', $classGroup) }}" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Final Report
                        </a>
                        <a href="{{ route('teacher.assessments') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Assessments
                        </a>
                    </div>
                </div>

                <!-- Assessment Configuration Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Assessment Configuration
                    </h3>

                    @if($assessmentConfigs->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CA Percentage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Exam</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($assessmentConfigs as $config)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $config->subject->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $config->subject->code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $config->ca_percentage }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    {{ $config->final_exam_percentage }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('teacher.term-assessments.create', ['classGroup' => $classGroup->id, 'subject' => $config->subject->id]) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Create Assessment</a>
                                                <a href="{{ route('teacher.assessments.config.edit', $config) }}" 
                                                   class="text-gray-600 hover:text-gray-900">Edit Config</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800">No assessment configurations</h4>
                                    <p class="text-sm text-yellow-700 mt-1">Configure assessment settings for your subjects to get started.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Term Assessments Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Term Assessments
                    </h3>

                    @if($termAssessments->count() > 0)
                        <div class="space-y-6">
                            @foreach($termAssessments as $termId => $assessments)
                                @php $term = $assessments->first()->term; @endphp
                                <div class="bg-white border border-gray-200 rounded-lg">
                                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                        <h4 class="text-md font-semibold text-gray-900">
                                            {{ $term->name }}
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $term->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $term->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $term->start_date->format('M j, Y') }} - {{ $term->end_date->format('M j, Y') }}
                                        </p>
                                    </div>
                                    <div class="p-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($assessments as $assessment)
                                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <a href="{{ route('teacher.term-assessments.show', $assessment) }}" 
                                                           class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                            {{ $assessment->title }}
                                                        </a>
                                                        <div class="flex space-x-1">
                                                            <a href="{{ route('teacher.term-assessments.edit', $assessment) }}"
                                                               class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded hover:bg-yellow-200 transition-colors"
                                                               title="Edit Assessment">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                                Edit
                                                            </a>
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $assessment->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                {{ $assessment->is_published ? 'Published' : 'Draft' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm text-gray-600 mb-2">{{ $assessment->subject->name }}</p>
                                                    <div class="flex justify-between text-xs text-gray-500">
                                                        <span>Max Score: <strong>{{ $assessment->max_score }}</strong></span>
                                                        <span>Due: <strong>{{ $assessment->due_date->format('M j, Y') }}</strong></span>
                                                    </div>
                                                    
                                                    <!-- NEW: Show CA Allocation and Weight -->
                                                    <div class="mt-2 pt-2 border-t border-gray-100">
                                                        <div class="flex justify-between text-xs">
                                                            <span class="text-gray-500">
                                                                CA Type: 
                                                                <strong class="text-indigo-600">{{ ucfirst(str_replace('_', ' ', $assessment->ca_allocation_type)) }}</strong>
                                                            </span>
                                                            <span class="text-gray-500">
                                                                Weight: 
                                                                <strong class="text-green-600">{{ $assessment->total_weight_percentage }}%</strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">No term assessments yet</h4>
                                    <p class="text-sm text-blue-700 mt-1">Create term assessments to track student progress throughout the academic year.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection