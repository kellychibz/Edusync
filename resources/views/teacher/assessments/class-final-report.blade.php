@extends('layouts.app')
@php use App\Helpers\GradeHelper; @endphp

@section('title', 'Final Class Report - ' . $classGroup->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Final Class Report - {{ $classGroup->name }}</h2>
                        <p class="text-gray-600 mt-1">
                            Academic Year: {{ $academicYear->year }} • 
                            {{ $classGroup->gradeLevel->name ?? '' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.assessments.class', $classGroup) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            Back to Assessments
                        </a>
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Print Report
                        </button>
                    </div>
                </div>

                <!-- Class Summary -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-600">Class Average</p>
                        <p class="text-2xl font-bold text-blue-900">{{ number_format($classStatistics['overall_average'], 1) }}%</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-green-600">Students</p>
                        <p class="text-2xl font-bold text-green-900">{{ $classStatistics['total_students'] }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-purple-600">Subjects</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $assessmentConfigs->count() }}</p>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-orange-600">Top Student</p>
                        @php
                            $topStudent = collect($studentReports)->sortByDesc('overall_score')->first();
                        @endphp
                        <p class="text-lg font-bold text-orange-900">
                            {{ $topStudent['student']->user->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-orange-700">{{ number_format($topStudent['overall_score'] ?? 0, 1) }}%</p>
                    </div>
                </div>

                <!-- Term Averages -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Term Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($terms as $term)
                            @php
                                $termStats = $classStatistics['terms'][$term->term_number] ?? null;
                                $average = $termStats['average'] ?? 0;
                                $grade = GradeHelper::calculateGradeLetter($average);
                                $gradeColor = GradeHelper::getGradeColor($grade);
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $term->name }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradeColor }}">
                                        {{ $grade }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $term->start_date->format('M j') }} - {{ $term->end_date->format('M j, Y') }}
                                </p>
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-gray-800">{{ number_format($average, 1) }}%</p>
                                    <p class="text-sm text-gray-500">Class Average</p>
                                </div>
                                @if($term->term_number == 3)
                                    <div class="mt-3 pt-3 border-t border-gray-100 text-sm">
                                        <p class="text-gray-600">Final Exam Weight</p>
                                        <p class="font-medium">
                                            @foreach($assessmentConfigs as $config)
                                                {{ $config->final_exam_percentage }}%
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Performance Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Student Performance</h3>
                    <div class="text-sm text-gray-600">
                        Showing {{ $students->count() }} students
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rank
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Admission No.
                                </th>
                                
                                <!-- Term Columns -->
                                @foreach($terms as $term)
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $term->name }}
                                    </th>
                                @endforeach
                                
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Overall
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(collect($studentReports)->sortByDesc('overall_score') as $report)
                                <tr class="hover:bg-gray-50">
                                    <!-- Rank -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                            {{ $report['rank'] <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $report['rank'] }}
                                        </span>
                                    </td>
                                    
                                    <!-- Student Info -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $report['student']->user->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report['student']->admission_number }}
                                    </td>
                                    
                                    <!-- Term Scores -->
                                    @foreach($terms as $term)
                                        @php
                                            $subjectId = $assessmentConfigs->first()->subject_id ?? null;
                                            $termScore = $report['subjects'][$subjectId]['term_scores'][$term->term_number] ?? null;
                                        @endphp
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($termScore && $termScore['has_all_grades'])
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-gray-900">
                                                        {{ number_format($termScore['percentage'], 1) }}%
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ GradeHelper::getGradeColor($termScore['grade']) }}">
                                                            {{ $termScore['grade'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center text-gray-400">—</div>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <!-- Overall Score -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-gray-900">
                                                {{ number_format($report['overall_score'], 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Final Grade -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ GradeHelper::getGradeColor($report['overall_grade']) }}">
                                                {{ $report['overall_grade'] }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Grading Scale</h4>
                    <div class="grid grid-cols-5 gap-2">
                        <div class="text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">A</span>
                            <p class="text-xs text-gray-600 mt-1">90%+</p>
                        </div>
                        <div class="text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">B</span>
                            <p class="text-xs text-gray-600 mt-1">80-89%</p>
                        </div>
                        <div class="text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">C</span>
                            <p class="text-xs text-gray-600 mt-1">70-79%</p>
                        </div>
                        <div class="text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">D</span>
                            <p class="text-xs text-gray-600 mt-1">60-69%</p>
                        </div>
                        <div class="text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">F</span>
                            <p class="text-xs text-gray-600 mt-1">Below 60%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print { display: none !important; }
    body { font-size: 10pt; }
    table { font-size: 9pt; }
    .py-12 { padding-top: 0 !important; padding-bottom: 0 !important; }
    .shadow-sm { box-shadow: none !important; }
    .border { border-color: #ccc !important; }
}
</style>
@endsection