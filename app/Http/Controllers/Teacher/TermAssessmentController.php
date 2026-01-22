<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TermAssessment;
use App\Models\ClassGroup;
use App\Models\Subject;
use App\Models\Term;
use App\Models\GradeTask;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AssessmentConfig;
use App\Helpers\GradeHelper;

class TermAssessmentController extends Controller
{
    public function create($classGroupId, $subjectId)
    {
        $teacher = Auth::user()->teacher;
        $classGroup = ClassGroup::findOrFail($classGroupId);
        $subject = Subject::findOrFail($subjectId);
        $currentAcademicYear = AcademicYear::active()->first();

        // Get active terms for the current academic year
        $terms = Term::where('academic_year_id', $currentAcademicYear->id)->get();
        
        // Get existing grade tasks for this class and subject
        $gradeTasks = GradeTask::where('class_group_id', $classGroupId)
            ->where('subject_id', $subjectId)
            ->where('teacher_id', $teacher->id)
            ->get();

        // ===== NEW: GET ASSESSMENT CONFIG =====
        $assessmentConfig = AssessmentConfig::where('class_group_id', $classGroupId)
            ->where('subject_id', $subjectId)
            ->where('teacher_id', Auth::id()) // CHANGED: Use Auth::id() instead of $teacher->id
            ->first();
        
        // Calculate CA allocation per term
        $caPercentage = $assessmentConfig ? $assessmentConfig->ca_percentage : 0;
        $finalExamPercentage = $assessmentConfig ? $assessmentConfig->final_exam_percentage : 0;
        $termAllocation = $caPercentage / 2; // 50/50 split between Term 1 and 2
        // ===== END NEW =====

        // DEBUG: Log what we're sending to the view
        Log::info('Term Assessment Create - Data:', [
            'class_group' => $classGroup->name,
            'subject' => $subject->name,
            'user_id' => Auth::id(), // Log user ID
            'teacher_model_id' => $teacher->id, // Log teacher model ID
            'grade_tasks_count' => $gradeTasks->count(),
            'terms_count' => $terms->count(),
            'ca_percentage' => $caPercentage,
            'has_config' => !is_null($assessmentConfig)
        ]);
        
        // Verify the teacher teaches this class and subject
        $teacherSubject = $classGroup->subjects()->where('teacher_id', $teacher->id)->where('subject_id', $subjectId)->first();
        if (!$teacherSubject) {
            abort(403, 'You are not assigned to teach this subject in this class.');
        }

        return view('teacher.assessments.term-assessment-create', compact(
            'classGroup',
            'subject',
            'terms',
            'gradeTasks',
            'currentAcademicYear',
            'assessmentConfig',
            'caPercentage',
            'finalExamPercentage',
            'termAllocation'
        ));
    }

    public function store(Request $request)
    {
        Log::info('=== TERM ASSESSMENT STORE METHOD CALLED ===');
        Log::info('Request data:', $request->all());

        $teacher = Auth::user()->teacher;
        $userId = Auth::id(); // Get user ID once

        try {
            $request->validate([
                'class_group_id' => 'required|exists:class_groups,id',
                'subject_id' => 'required|exists:subjects,id',
                'term_id' => 'required|exists:terms,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'max_score' => 'required|numeric|min:1|max:1000',
                'due_date' => 'required|date',
                'tasks' => 'required|array',
                'tasks.*.grade_task_id' => 'required|exists:grade_tasks,id',
                'tasks.*.weight_percentage' => 'required|integer|min:0|max:100',
            ]);

            Log::info('Form validation passed');

            // Verify the teacher teaches this class and subject
            $classGroup = ClassGroup::findOrFail($request->class_group_id);
            $teacherSubject = $classGroup->subjects()->where('teacher_id', $teacher->id)->where('subject_id', $request->subject_id)->first();
            if (!$teacherSubject) {
                Log::warning('Teacher not assigned to this class/subject');
                return redirect()->back()->withErrors(['error' => 'You are not assigned to teach this subject in this class.'])->withInput();
            }

            Log::info('Teacher authorization passed');

            // ===== NEW: GET ASSESSMENT CONFIG AND CALCULATE CA ALLOCATION =====
            $config = AssessmentConfig::where('class_group_id', $request->class_group_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $userId) // CHANGED: Use $userId instead of $teacher->id
                ->first();

            if (!$config) {
                Log::warning('No assessment config found for this class/subject');
                return redirect()->back()->withErrors(['error' => 'Please configure assessment percentages first.'])->withInput();
            }

            $term = Term::findOrFail($request->term_id);
            $caPercentage = $config->ca_percentage;
            $finalExamPercentage = $config->final_exam_percentage;

            // Determine CA allocation based on term
            $caAllocationType = 'term1_ca';
            $isFinalExam = false;
            $totalWeightPercentage = 0;

            if ($term->term_number == 1) {
                $caAllocationType = 'term1_ca';
                $totalWeightPercentage = $caPercentage / 2; // 50% of CA for Term 1
            } elseif ($term->term_number == 2) {
                $caAllocationType = 'term2_ca';
                $totalWeightPercentage = $caPercentage / 2; // 50% of CA for Term 2
            } elseif ($term->term_number == 3) {
                $caAllocationType = 'final_exam';
                $isFinalExam = true;
                $totalWeightPercentage = $finalExamPercentage; // Full final exam percentage
            }

            Log::info('CA Allocation calculated:', [
                'term_number' => $term->term_number,
                'ca_percentage' => $caPercentage,
                'final_exam_percentage' => $finalExamPercentage,
                'ca_allocation_type' => $caAllocationType,
                'total_weight_percentage' => $totalWeightPercentage,
                'is_final_exam' => $isFinalExam
            ]);

            // Check existing assessments in this term to ensure we don't exceed allocation
            $existingWeight = TermAssessment::where('term_id', $request->term_id)
                ->where('class_group_id', $request->class_group_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $userId) // CHANGED: Use $userId instead of $teacher->id
                ->sum('total_weight_percentage');

            $availableAllocation = $totalWeightPercentage - $existingWeight;

            Log::info('Existing weight check:', [
                'existing_weight' => $existingWeight,
                'available_allocation' => $availableAllocation
            ]);

            // For term assessments, we'll use the full term allocation (e.g., 20% for a term)
            // You might want to make this configurable or allow partial allocations
            $newAssessmentWeight = $totalWeightPercentage; // Using full term allocation

            if ($availableAllocation <= 0) {
                Log::warning('CA allocation exceeded for this term');
                return redirect()->back()->withErrors([
                    'term_id' => "CA allocation for this term is already used. Available: {$availableAllocation}%"
                ])->withInput();
            }
            // ===== END NEW CA ALLOCATION LOGIC =====

            // Only sum weights that are greater than 0
            $nonZeroWeights = array_filter(array_column($request->tasks, 'weight_percentage'), function($weight) {
                return $weight > 0;
            });
            $totalTaskWeight = array_sum($nonZeroWeights);

            Log::info('Total task weight calculated:', ['total' => $totalTaskWeight, 'non_zero_weights' => $nonZeroWeights]);

            if ($totalTaskWeight !== 100) {
                Log::warning('Total task weight not 100%', ['total' => $totalTaskWeight]);
                return redirect()->back()->withErrors(['tasks' => 'Total task weight percentage must equal 100%'])->withInput();       
            }

            Log::info('Weight validation passed');

            // Create the term assessment WITH NEW FIELDS
            $termAssessment = TermAssessment::create([
                'class_group_id' => $request->class_group_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $userId, // CHANGED: Use $userId instead of $teacher->id
                'term_id' => $request->term_id,
                'title' => $request->title,
                'description' => $request->description,
                'max_score' => $request->max_score,
                'total_weight_percentage' => $newAssessmentWeight,
                'ca_allocation_type' => $caAllocationType,
                'is_final_exam' => $isFinalExam,
                'due_date' => $request->due_date,
                'is_published' => false,
            ]);

            Log::info('Term assessment created:', ['id' => $termAssessment->id]);

            // Create term assessment tasks with FINAL WEIGHT CALCULATION
            foreach ($request->tasks as $task) {
                if ($task['weight_percentage'] > 0) {
                    // Calculate final weight percentage: task weight × total weight percentage / 100
                    $finalWeightPercentage = ($task['weight_percentage'] * $newAssessmentWeight) / 100;
                    
                    $termAssessment->tasks()->create([
                        'grade_task_id' => $task['grade_task_id'],
                        'weight_percentage' => $task['weight_percentage'],
                        'final_weight_percentage' => $finalWeightPercentage,
                    ]);

                    Log::info('Task created with final weight:', [
                        'grade_task_id' => $task['grade_task_id'],
                        'weight_percentage' => $task['weight_percentage'],
                        'final_weight_percentage' => $finalWeightPercentage
                    ]);
                }
            }

            Log::info('Term assessment tasks created');

            return redirect()->route('teacher.assessments.class', $request->class_group_id)
                ->with('success', 'Term assessment created successfully.')
                ->with('info', "This assessment contributes {$newAssessmentWeight}% to the final grade.");

        } catch (\Exception $e) {
            Log::error('Error creating term assessment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Error creating assessment: ' . $e->getMessage()])->withInput();    
        }
    }

    public function show(TermAssessment $termAssessment)
    {
        // Authorization - ensure teacher owns this assessment
        if ($termAssessment->teacher_id !== Auth::id()) {
            abort(403);
        }

        $termAssessment->load(['tasks.gradeTask', 'term', 'classGroup', 'subject']);

        return view('teacher.assessments.term-assessment-show', compact('termAssessment'));
    }

    public function viewScores(TermAssessment $termAssessment)
    {
        // Authorization - ensure teacher owns this assessment
        if ($termAssessment->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Load all necessary relationships
        $termAssessment->load([
            'tasks.gradeTask',
            'term',
            'classGroup',
            'subject',
            'classGroup.students.user',
            'classGroup.students.gradeEntries' => function($query) use ($termAssessment) {
                $query->whereIn('task_id', $termAssessment->tasks->pluck('grade_task_id'));
            }
        ]);

        // Get students in this class
        $students = $termAssessment->classGroup->students;

        // Calculate scores for each student
        $studentScores = [];
        $totalScores = [];
        $hasMissingGrades = false;

        foreach ($students as $student) {
            $studentScore = [
                'student' => $student,
                'tasks' => [],
                'total_percentage' => 0,
                'weighted_score' => 0,
                'has_missing' => false
            ];

            $totalTaskScore = 0;
            $totalWeight = 0;

            foreach ($termAssessment->tasks as $task) {
                $gradeEntry = $student->gradeEntries->firstWhere('task_id', $task->grade_task_id);
                
                if ($gradeEntry && $gradeEntry->score !== null) {
                    $taskPercentage = ($gradeEntry->score / $task->gradeTask->max_score) * 100;
                    $weightedScore = ($taskPercentage * $task->weight_percentage) / 100;
                    
                    $studentScore['tasks'][$task->id] = [
                        'score' => $gradeEntry->score,
                        'max_score' => $task->gradeTask->max_score,
                        'percentage' => $taskPercentage,
                        'weight_percentage' => $task->weight_percentage,
                        'weighted_score' => $weightedScore,
                        'has_grade' => true
                    ];

                    $totalTaskScore += $weightedScore;
                    $totalWeight += $task->weight_percentage;
                } else {
                    $studentScore['tasks'][$task->id] = [
                        'score' => null,
                        'max_score' => $task->gradeTask->max_score,
                        'percentage' => 0,
                        'weight_percentage' => $task->weight_percentage,
                        'weighted_score' => 0,
                        'has_grade' => false
                    ];
                    $studentScore['has_missing'] = true;
                    $hasMissingGrades = true;
                }
            }

            // Calculate total percentage (only if all tasks have grades)
            if (!$studentScore['has_missing'] && $totalWeight > 0) {
                $studentScore['total_percentage'] = ($totalTaskScore / $totalWeight) * 100;
                $studentScore['weighted_score'] = ($studentScore['total_percentage'] * $termAssessment->total_weight_percentage) / 100;
            }

            // Determine grade letter
            $studentScore['grade_letter'] = GradeHelper::calculateGradeLetter($studentScore['total_percentage']);
            
            $studentScores[$student->id] = $studentScore;
        }

        // Calculate class statistics
        $completedScores = array_filter($studentScores, fn($score) => !$score['has_missing']);
        $classAverage = count($completedScores) > 0 
            ? array_sum(array_column($completedScores, 'total_percentage')) / count($completedScores)
            : 0;

        return view('teacher.assessments.term-assessment-scores', compact(
            'termAssessment',
            'students',
            'studentScores',
            'hasMissingGrades',
            'classAverage'
        ));
    }

    public function finalClassReport(ClassGroup $classGroup)
    {
        // Authorization - ensure teacher teaches this class
        $teacher = Auth::user()->teacher;
        $teacherSubject = $classGroup->subjects()->where('teacher_id', $teacher->id)->first();
        if (!$teacherSubject) {
            abort(403, 'You are not assigned to teach any subject in this class.');
        }

        // Get active academic year
        $academicYear = AcademicYear::active()->first();
        if (!$academicYear) {
            return redirect()->back()->withErrors(['error' => 'No active academic year found.']);
        }

        // Get all terms for this academic year
        $terms = Term::where('academic_year_id', $academicYear->id)
            ->orderBy('term_number')
            ->get();

        // Get all students in this class
        $students = $classGroup->students()
            ->with(['user', 'gradeEntries.task', 'classGroups'])
            ->get();

        // Get assessment configs for this class (for this teacher)
        $assessmentConfigs = AssessmentConfig::where('class_group_id', $classGroup->id)
            ->where('teacher_id', Auth::id())
            ->with('subject')
            ->get();

        // Get all term assessments for this class (for this teacher)
        $termAssessments = TermAssessment::where('class_group_id', $classGroup->id)
            ->where('teacher_id', Auth::id())
            ->with(['term', 'subject', 'tasks.gradeTask'])
            ->get()
            ->groupBy(['subject_id', 'term_id']);

        // Calculate final grades for each student
        $studentReports = [];
        $classStatistics = [
            'total_students' => $students->count(),
            'terms' => [],
            'overall_average' => 0,
            'grade_distribution' => [
                'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0
            ]
        ];

        // Initialize term statistics
        foreach ($terms as $term) {
            $classStatistics['terms'][$term->term_number] = [
                'name' => $term->name,
                'average' => 0,
                'total_score' => 0,
                'student_count' => 0
            ];
        }

        foreach ($students as $student) {
            $studentReport = [
                'student' => $student,
                'terms' => [],
                'subjects' => [],
                'overall_score' => 0,
                'overall_grade' => 'N/A',
                'rank' => 0
            ];

            $totalSubjectScore = 0;
            $subjectCount = 0;

            foreach ($assessmentConfigs as $config) {
                $subjectId = $config->subject_id;
                $subjectReport = [
                    'subject' => $config->subject,
                    'term_scores' => [],
                    'final_score' => 0,
                    'grade' => 'N/A'
                ];

                $totalTermWeight = 0;
                $weightedScore = 0;

                // Calculate scores for each term
                foreach ($terms as $term) {
                    $termScore = $this->calculateStudentTermScore($student, $subjectId, $term, $config);
                    
                    $subjectReport['term_scores'][$term->term_number] = $termScore;
                    
                    if ($termScore['has_all_grades']) {
                        $weightedScore += $termScore['weighted_score'];
                        $totalTermWeight += $termScore['term_weight'];
                        
                        // Accumulate for term statistics
                        $classStatistics['terms'][$term->term_number]['total_score'] += $termScore['percentage'];
                        $classStatistics['terms'][$term->term_number]['student_count']++;
                    }
                }

                // Calculate final subject percentage
                if ($totalTermWeight > 0) {
                    $subjectReport['final_score'] = ($weightedScore / $totalTermWeight) * 100;
                    $subjectReport['grade'] = GradeHelper::calculateGradeLetter($subjectReport['final_score']);
                    
                    $totalSubjectScore += $subjectReport['final_score'];
                    $subjectCount++;
                }

                $studentReport['subjects'][$subjectId] = $subjectReport;
            }

            // Calculate overall score (average of all subjects)
            if ($subjectCount > 0) {
                $studentReport['overall_score'] = $totalSubjectScore / $subjectCount;
                $studentReport['overall_grade'] = GradeHelper::calculateGradeLetter($studentReport['overall_score']);
                
                // Track for class statistics
                $classStatistics['grade_distribution'][$studentReport['overall_grade']]++;
                $classStatistics['overall_average'] += $studentReport['overall_score'];
            }

            $studentReports[$student->id] = $studentReport;
        }

        // Calculate class statistics
        if ($students->count() > 0) {
            $classStatistics['overall_average'] = $classStatistics['overall_average'] / $students->count();
            
            // Calculate term averages
            foreach ($terms as $term) {
                if ($classStatistics['terms'][$term->term_number]['student_count'] > 0) {
                    $classStatistics['terms'][$term->term_number]['average'] = 
                        $classStatistics['terms'][$term->term_number]['total_score'] / 
                        $classStatistics['terms'][$term->term_number]['student_count'];
                }
            }
            
            // Calculate ranks
            $sortedStudents = collect($studentReports)->sortByDesc('overall_score');
            $rank = 1;
            foreach ($sortedStudents as $studentId => $report) {
                $studentReports[$studentId]['rank'] = $rank++;
            }
        }

        return view('teacher.assessments.class-final-report', compact(
            'classGroup',
            'academicYear',
            'terms',
            'students',
            'assessmentConfigs',
            'studentReports',
            'classStatistics'
        ));
    }

    // Updated helper method to calculate student score for a term and subject
    private function calculateStudentTermScore($student, $subjectId, $term, $config)
    {
        // Get the class group ID from student's class groups relationship
        $classGroupId = $student->classGroups()->first()->id ?? null;
        
        if (!$classGroupId) {
            return [
                'percentage' => null,
                'score' => 0,
                'weighted_score' => 0,
                'has_all_grades' => false,
                'term_weight' => $term->term_number == 3 ? 
                    $config->final_exam_percentage : 
                    ($config->ca_percentage / 2),
                'grade' => 'N/A'
            ];
        }

        // Get all assessments for this subject and term
        $assessments = TermAssessment::where('class_group_id', $classGroupId)
            ->where('subject_id', $subjectId)
            ->where('term_id', $term->id)
            ->where('teacher_id', Auth::id())
            ->with(['tasks.gradeTask'])
            ->get();

        $totalTaskScore = 0;
        $totalWeight = 0;
        $hasAllGrades = true;

        foreach ($assessments as $assessment) {
            foreach ($assessment->tasks as $task) {
                $gradeEntry = $student->gradeEntries->firstWhere('task_id', $task->grade_task_id);
                
                if ($gradeEntry && $gradeEntry->score !== null) {
                    $taskPercentage = ($gradeEntry->score / $task->gradeTask->max_score) * 100;
                    $weightedTaskScore = ($taskPercentage * $task->weight_percentage) / 100;
                    
                    $totalTaskScore += $weightedTaskScore;
                    $totalWeight += $task->weight_percentage;
                } else {
                    $hasAllGrades = false;
                }
            }
        }

        $termPercentage = ($totalWeight > 0 && $hasAllGrades) ? ($totalTaskScore / $totalWeight) * 100 : 0;
        
        // Determine term weight
        $termWeight = $term->term_number == 3 ? 
            $config->final_exam_percentage : 
            ($config->ca_percentage / 2);
        
        $weightedScore = ($termPercentage * $termWeight) / 100;

        return [
            'percentage' => $hasAllGrades ? $termPercentage : null,
            'score' => $hasAllGrades ? $termPercentage : 0,
            'weighted_score' => $weightedScore,
            'has_all_grades' => $hasAllGrades,
            'term_weight' => $termWeight,
            'grade' => $hasAllGrades ? GradeHelper::calculateGradeLetter($termPercentage) : 'N/A'
        ];
    }

    public function edit(TermAssessment $termAssessment)
    {
        // Authorization - ensure teacher owns this assessment
        if ($termAssessment->teacher_id !== Auth::id()) {
            abort(403);
        }

        $teacher = Auth::user()->teacher;
        $classGroup = $termAssessment->classGroup;
        $subject = $termAssessment->subject;
        $currentAcademicYear = AcademicYear::active()->first();

        // Get active terms for the current academic year
        $terms = Term::where('academic_year_id', $currentAcademicYear->id)->get();
        
        // Get existing grade tasks for this class and subject
        $gradeTasks = GradeTask::where('class_group_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_id', $teacher->id)
            ->get();

        // Get assessment config
        $assessmentConfig = AssessmentConfig::where('class_group_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_id', Auth::id())
            ->first();

        // Calculate remaining allocation for this term
        $caPercentage = $assessmentConfig ? $assessmentConfig->ca_percentage : 0;
        $finalExamPercentage = $assessmentConfig ? $assessmentConfig->final_exam_percentage : 0;
        
        // Determine term weight based on term number
        $termWeight = $termAssessment->term->term_number == 3 ? 
            $finalExamPercentage : 
            ($caPercentage / 2);

        // Get existing weight used in this term (excluding current assessment for edit)
        $existingWeight = TermAssessment::where('term_id', $termAssessment->term_id)
            ->where('class_group_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_id', Auth::id())
            ->where('id', '!=', $termAssessment->id) // Exclude current assessment
            ->sum('total_weight_percentage');

        $availableAllocation = $termWeight - $existingWeight;
        $currentAllocation = $termAssessment->total_weight_percentage;

        // Get current tasks with their weights
        $currentTasks = $termAssessment->tasks()->with('gradeTask')->get();

        return view('teacher.assessments.term-assessment-edit', compact(
            'termAssessment',
            'classGroup',
            'subject',
            'terms',
            'gradeTasks',
            'currentAcademicYear',
            'assessmentConfig',
            'caPercentage',
            'finalExamPercentage',
            'availableAllocation',
            'currentAllocation',
            'currentTasks',
            'termWeight'
        ));
    }

    public function update(Request $request, TermAssessment $termAssessment)
    {
        // Authorization - ensure teacher owns this assessment
        if ($termAssessment->teacher_id !== Auth::id()) {
            abort(403);
        }

        Log::info('=== TERM ASSESSMENT UPDATE METHOD CALLED ===');
        Log::info('Request data:', $request->all());

        $userId = Auth::id();

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'max_score' => 'required|numeric|min:1|max:1000',
                'due_date' => 'required|date',
                'term_id' => 'required|exists:terms,id',
                'total_weight_percentage' => 'required|numeric|min:1',
                'tasks' => 'required|array',
                'tasks.*.grade_task_id' => 'required|exists:grade_tasks,id',
                'tasks.*.weight_percentage' => 'required|integer|min:0|max:100',
            ]);

            Log::info('Form validation passed');

            // Get assessment config
            $config = AssessmentConfig::where('class_group_id', $termAssessment->class_group_id)
                ->where('subject_id', $termAssessment->subject_id)
                ->where('teacher_id', $userId)
                ->first();

            if (!$config) {
                Log::warning('No assessment config found for this class/subject');
                return redirect()->back()->withErrors(['error' => 'Assessment configuration not found.'])->withInput();
            }

            $term = Term::findOrFail($request->term_id);
            $caPercentage = $config->ca_percentage;
            $finalExamPercentage = $config->final_exam_percentage;

            // Determine CA allocation based on term
            $caAllocationType = 'term1_ca';
            $isFinalExam = false;
            $totalWeightPercentage = 0;

            if ($term->term_number == 1) {
                $caAllocationType = 'term1_ca';
                $totalWeightPercentage = $caPercentage / 2;
            } elseif ($term->term_number == 2) {
                $caAllocationType = 'term2_ca';
                $totalWeightPercentage = $caPercentage / 2;
            } elseif ($term->term_number == 3) {
                $caAllocationType = 'final_exam';
                $isFinalExam = true;
                $totalWeightPercentage = $finalExamPercentage;
            }

            // Check existing assessments in this term (excluding current assessment)
            $existingWeight = TermAssessment::where('term_id', $request->term_id)
                ->where('class_group_id', $termAssessment->class_group_id)
                ->where('subject_id', $termAssessment->subject_id)
                ->where('teacher_id', $userId)
                ->where('id', '!=', $termAssessment->id) // Exclude current assessment
                ->sum('total_weight_percentage');

            $requestedWeight = $request->total_weight_percentage;
            $availableAllocation = $totalWeightPercentage - $existingWeight;
            $currentAllocation = $termAssessment->total_weight_percentage;

            Log::info('Weight allocation check:', [
                'existing_weight' => $existingWeight,
                'available_allocation' => $availableAllocation,
                'current_allocation' => $currentAllocation,
                'requested_weight' => $requestedWeight,
                'total_weight_percentage' => $totalWeightPercentage
            ]);

            // Validate weight allocation
            if ($requestedWeight > ($availableAllocation + $currentAllocation)) {
                Log::warning('Requested weight exceeds available allocation');
                return redirect()->back()->withErrors([
                    'total_weight_percentage' => "Requested weight ({$requestedWeight}%) exceeds available allocation. Available: {$availableAllocation}% plus current: {$currentAllocation}%"
                ])->withInput();
            }

            // Validate task weights total 100%
            $nonZeroWeights = array_filter(array_column($request->tasks, 'weight_percentage'), function($weight) {
                return $weight > 0;
            });
            $totalTaskWeight = array_sum($nonZeroWeights);

            Log::info('Total task weight calculated:', ['total' => $totalTaskWeight, 'non_zero_weights' => $nonZeroWeights]);

            if ($totalTaskWeight !== 100) {
                Log::warning('Total task weight not 100%', ['total' => $totalTaskWeight]);
                return redirect()->back()->withErrors(['tasks' => 'Total task weight percentage must equal 100%'])->withInput();       
            }

            Log::info('Weight validation passed');

            // Update the term assessment
            $termAssessment->update([
                'title' => $request->title,
                'description' => $request->description,
                'max_score' => $request->max_score,
                'total_weight_percentage' => $requestedWeight,
                'ca_allocation_type' => $caAllocationType,
                'is_final_exam' => $isFinalExam,
                'due_date' => $request->due_date,
                'term_id' => $request->term_id,
            ]);

            Log::info('Term assessment updated:', ['id' => $termAssessment->id]);

            // Delete existing tasks
            $termAssessment->tasks()->delete();
            Log::info('Existing tasks deleted');

            // Create new term assessment tasks with FINAL WEIGHT CALCULATION
            foreach ($request->tasks as $gradeTaskId => $taskData) {
                if ($taskData['weight_percentage'] > 0) {
                    // Calculate final weight percentage: task weight × total weight percentage / 100
                    $finalWeightPercentage = ($taskData['weight_percentage'] * $requestedWeight) / 100;
                    
                    $termAssessment->tasks()->create([
                        'grade_task_id' => $gradeTaskId,
                        'weight_percentage' => $taskData['weight_percentage'],
                        'final_weight_percentage' => $finalWeightPercentage,
                    ]);

                    Log::info('Task created with final weight:', [
                        'grade_task_id' => $gradeTaskId,
                        'weight_percentage' => $taskData['weight_percentage'],
                        'final_weight_percentage' => $finalWeightPercentage
                    ]);
                }
            }

            Log::info('Term assessment tasks updated');

            return redirect()->route('teacher.term-assessments.show', $termAssessment)
                ->with('success', 'Term assessment updated successfully.')
                ->with('info', "This assessment now contributes {$requestedWeight}% to the final grade.");

        } catch (\Exception $e) {
            Log::error('Error updating term assessment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Error updating assessment: ' . $e->getMessage()])->withInput();    
        }
    }

    public function destroy(TermAssessment $termAssessment)
    {
        // Authorization - ensure teacher owns this assessment
        if ($termAssessment->teacher_id !== Auth::id()) {
            abort(403);
        }

        Log::info('=== TERM ASSESSMENT DELETE METHOD CALLED ===', ['id' => $termAssessment->id]);

        try {
            $classGroupId = $termAssessment->class_group_id;
            $assessmentTitle = $termAssessment->title;
            
            // Delete associated tasks first (cascade delete)
            $termAssessment->tasks()->delete();
            Log::info('Assessment tasks deleted', ['assessment_id' => $termAssessment->id]);
            
            // Delete the assessment
            $termAssessment->delete();
            Log::info('Assessment deleted', ['id' => $termAssessment->id]);

            return redirect()->route('teacher.assessments.class', $classGroupId)
                ->with('success', "Assessment '{$assessmentTitle}' has been deleted successfully.");

        } catch (\Exception $e) {
            Log::error('Error deleting term assessment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('teacher.term-assessments.show', $termAssessment)
                ->withErrors(['error' => 'Error deleting assessment: ' . $e->getMessage()]);
        }
    }
}