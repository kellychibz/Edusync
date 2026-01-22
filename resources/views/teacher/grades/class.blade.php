@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">{{ $classGroup->name }} - {{ $subject->name }}</h2>
                        <p class="text-gray-600 mt-1">Grade Level: {{ $classGroup->gradeLevel->name }} | Students: {{ $students->count() }}</p>
                    </div>
                    <a href="{{ route('teacher.grades.task.create', $classGroup) }}" 
                       class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Task
                    </a>
                </div>

                <!-- Performance Chart -->
                <div class="mb-8">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Class Performance Overview</h3>
                        </div>
                        <div class="p-6">
                            <!-- Debug Info -->
                             <!-- 
                            <div class="mb-4 p-3 bg-gray-100 rounded text-sm">
                                <strong>Debug Chart Data:</strong><br>
                                Tasks: {{ count($performanceData['labels']) }}<br>
                                Labels: {{ implode(', ', $performanceData['labels']) }}<br>
                                Scores: {{ implode(', ', $performanceData['average_scores']) }}
                            </div>
                            -->
                            <div class="chart-bar" style="height: 300px;">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tasks List -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Grading Tasks</h3>
                    </div>
                    <div class="p-6">
                        @if($tasks->isEmpty())
                            <div class="text-center py-12">
                                <i class="fas fa-tasks fa-3x text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg mb-4">No grading tasks created yet.</p>
                                <a href="{{ route('teacher.grades.task.create', $classGroup) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Create First Task
                                </a>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task Title</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Score</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($tasks as $task)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $task->title }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                                        {{ $task->type }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $task->due_date ? $task->due_date->format('M j, Y') : 'No due date' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $task->max_score }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center">
                                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                            <div class="bg-{{ $task->completion_rate >= 80 ? 'green' : ($task->completion_rate >= 50 ? 'yellow' : 'red') }}-600 h-2.5 rounded-full" 
                                                                 style="width: {{ $task->completion_rate }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-gray-500">{{ round($task->completion_rate) }}%</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('teacher.grades.task.show', $task) }}" 
                                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Manage Grades
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
console.log('📊 Chart script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initializing performance chart...');
    
    const ctx = document.getElementById('performanceChart');
    
    if (!ctx) {
        console.error('❌ Chart canvas element not found!');
        return;
    }
    
    console.log('📈 Chart data:', {
        labels: @json($performanceData['labels']),
        sco res: @json($performanceData['average_scores'])
    });

    try {
        const performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($performanceData['labels']),
                datasets: [{
                    label: 'Average Score (%)',
                    data: @json($performanceData['average_scores']),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Average Score (%)'
                        }
                    }
                }
            }
        });
        
        console.log('✅ Chart created successfully');
    } catch (error) {
        console.error('❌ Chart creation failed:', error);
    }
});
</script>
