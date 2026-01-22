@extends('layouts.app')
@php use App\Helpers\GradeHelper; @endphp

@section('title', 'Term Scores - ' . $termAssessment->title)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto">
        <!-- Printable Header -->
        <div class="print:block hidden bg-white p-6 mb-6 border-b">
            <div class="text-center">
                <h1 class="text-2xl font-bold">Term {{ $termAssessment->term->term_number }} Report</h1>
                <h2 class="text-xl">{{ $termAssessment->classGroup->name }} - {{ $termAssessment->subject->name }}</h2>
                <p class="text-gray-600">Teacher: {{ auth()->user()->name }} • Date: {{ now()->format('F j, Y') }}</p>
                <p class="text-gray-600">Assessment: {{ $termAssessment->title }}</p>
            </div>
        </div>

        <!-- Interactive Header (non-printable) -->
        <div class="print:hidden bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Term Scores: {{ $termAssessment->title }}</h2>
                        <p class="text-gray-600 mt-1">
                            {{ $termAssessment->classGroup->name }} - {{ $termAssessment->subject->name }}
                            • Term {{ $termAssessment->term->term_number }}
                            • Contributes {{ $termAssessment->total_weight_percentage }}% to final grade
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Report
                        </button>
                        <a href="{{ route('teacher.term-assessments.show', $termAssessment) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Back to Assessment
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-600">Class Average</p>
                        <p class="text-2xl font-bold text-blue-900">{{ number_format($classAverage, 1) }}%</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-green-600">Students</p>
                        <p class="text-2xl font-bold text-green-900">{{ $students->count() }}</p>
                    </div>
                    <div class="bg-{{ $hasMissingGrades ? 'yellow' : 'green' }}-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-{{ $hasMissingGrades ? 'yellow' : 'green' }}-600">Completion</p>
                        <p class="text-2xl font-bold text-{{ $hasMissingGrades ? 'yellow' : 'green' }}-900">
                            {{ $students->count() - count(array_filter($studentScores, fn($s) => $s['has_missing'])) }}/{{ $students->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scores Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Tasks Header -->
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-800 mb-2">Assessment Components</h3>
                    <div class="grid grid-cols-1 md:grid-cols-{{ min($termAssessment->tasks->count(), 4) }} gap-4">
                        @foreach($termAssessment->tasks as $task)
                            <div class="text-sm">
                                <span class="font-medium">{{ $task->gradeTask->title }}</span>
                                <span class="text-gray-600 ml-2">({{ $task->weight_percentage }}%)</span>
                                <p class="text-xs text-gray-500">
                                    Max: {{ $task->gradeTask->max_score }} • 
                                    Final: {{ number_format($task->final_weight_percentage, 1) }}%
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Students Scores Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 print:table">
                        <thead class="bg-gray-50 print:bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                @foreach($termAssessment->tasks as $task)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $task->gradeTask->title }}<br>
                                        <span class="text-xs font-normal">({{ $task->weight_percentage }}%)</span>
                                    </th>
                                @endforeach
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Score
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:hidden">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $student)
                                @php
                                    $score = $studentScores[$student->id] ?? null;
                                    $hasMissing = $score['has_missing'] ?? true;
                                @endphp
                                <tr class="{{ $hasMissing ? 'bg-yellow-50' : '' }} hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->admission_number }}</div>
                                    </td>
                                    
                                    @foreach($termAssessment->tasks as $task)
                                        @php
                                            $taskScore = $score['tasks'][$task->id] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($taskScore && $taskScore['has_grade'])
                                                <div class="text-sm text-gray-900">
                                                    {{ number_format($taskScore['score'], 1) }}/{{ $taskScore['max_score'] }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ number_format($taskScore['percentage'], 1) }}%
                                                    <br>
                                                    <span class="text-gray-400">
                                                        ({{ number_format($taskScore['weighted_score'], 1) }}%)
                                                    </span>
                                                </div>
                                            @else
                                                <div class="text-sm text-red-600 italic">Not graded</div>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$hasMissing)
                                            <div class="text-lg font-bold text-gray-900">
                                                {{ number_format($score['total_percentage'], 1) }}%
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Final: {{ number_format($score['weighted_score'], 1) }}%
                                            </div>
                                        @else
                                            <div class="text-sm text-red-600 italic">Incomplete</div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(!$hasMissing)
                                            @php
                                                $gradeLetter = $score['grade_letter'] ?? 'F';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ GradeHelper::getGradeColor($gradeLetter) }}">
                                                {{ $gradeLetter }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium print:hidden">
                                        @foreach($termAssessment->tasks as $task)
                                            @php
                                                $taskScore = $score['tasks'][$task->id] ?? null;
                                            @endphp
                                            @if(!$taskScore || !$taskScore['has_grade'])
                                                <a href="{{ route('teacher.grades.task.manage', $task->gradeTask) }}?student={{ $student->id }}" 
                                                   class="text-blue-600 hover:text-blue-900 block text-xs mb-1">
                                                    Grade {{ $task->gradeTask->title }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .print\:hidden {
        display: none !important;
    }
    .print\:block {
        display: block !important;
    }
    .print\:table {
        display: table !important;
    }
    body {
        font-size: 12px;
    }
    table {
        font-size: 10px;
    }
    .bg-gray-50 {
        background-color: #f9fafb !important;
    }
    .bg-yellow-50 {
        background-color: #fffbeb !important;
    }
}
</style>
@endsection