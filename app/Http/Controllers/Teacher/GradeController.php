<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\GradeTask;
use App\Models\GradeEntry;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ← ADD THIS LINE

class GradeController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        
        // Get classes assigned to this teacher with subjects
        $classes = ClassGroup::with(['subjects' => function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        }])
        ->whereHas('subjects', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })
        ->withCount('students')
        ->get();

        return view('teacher.grades.index', compact('classes', 'teacher'));
    }

    public function showClass(ClassGroup $classGroup)
    {
        $teacher = Auth::user()->teacher;
        
        // Verify the teacher teaches this class
        $subject = $classGroup->subjects()->where('teacher_id', $teacher->id)->first();
        if (!$subject) {
            abort(403, 'You are not assigned to teach this class.');
        }

        $students = $classGroup->students()->with('user')->get();
        $tasks = GradeTask::where('class_group_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->withCount(['gradeEntries as graded_count' => function($query) {
                $query->whereNotNull('score');
            }])
            ->orderBy('due_date', 'desc')
            ->get();

        // Calculate performance data for chart
        $performanceData = $this->getClassPerformanceData($classGroup, $subject);

        // DEBUG: Log what we're sending to the view
        Log::info("🎯 SHOW CLASS PAGE DATA", [
            'class_group' => $classGroup->name,
            'subject' => $subject->name,
            'student_count' => $students->count(),
            'task_count' => $tasks->count(),
            'performance_data_labels' => $performanceData['labels'],
            'performance_data_scores' => $performanceData['average_scores']
        ]);

        // DEBUG: Log each task's completion status
        foreach ($tasks as $task) {
            Log::info("📝 Task Completion", [
                'task_id' => $task->id,
                'task_title' => $task->title,
                'graded_count' => $task->graded_count,
                'total_students' => $students->count(),
                'completion_rate' => $students->count() > 0 ? round(($task->graded_count / $students->count()) * 100, 1) : 0
            ]);
        }

        return view('teacher.grades.class', compact(
            'classGroup', 
            'subject', 
            'students', 
            'tasks',
            'performanceData'
        ));
    }

    public function createTask(ClassGroup $classGroup)
    {
        $teacher = Auth::user()->teacher;
        $subject = $classGroup->subjects()->where('teacher_id', $teacher->id)->first();
        
        if (!$subject) {
            abort(403, 'You are not assigned to teach this class.');
        }

        return view('teacher.grades.create-task', compact('classGroup', 'subject'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'class_group_id' => 'required|exists:class_groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:assignment,quiz,test,exam,project,homework,participation',
            'max_score' => 'required|numeric|min:1|max:1000',
            'due_date' => 'nullable|date',
        ]);

        $teacher = Auth::user()->teacher;

        // Verify the teacher is assigned to this class and subject
        $classGroup = ClassGroup::findOrFail($request->class_group_id);
        $subject = $classGroup->subjects()->where('teacher_id', $teacher->id)->first();
        
        if (!$subject) {
            return redirect()->back()->with('error', 'You are not assigned to teach this class.');
        }

        $task = GradeTask::create([
            'class_group_id' => $request->class_group_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('teacher.grades.task.show', $task)
            ->with('success', 'Task created successfully. You can now add grades for students.');
    }

    public function showTask(GradeTask $task)
    {
        $teacher = Auth::user()->teacher;
        
        if ($task->teacher_id !== $teacher->id) {
            abort(403, 'You do not have permission to view this task.');
        }

        // DEBUG: Log that we're loading the view
        Log::info('🎯 SHOW TASK METHOD CALLED - Loading view for task: ' . $task->id);

        $students = $task->classGroup->students()->with(['user', 'gradeEntries' => function($query) use ($task) {
            $query->where('task_id', $task->id);
        }])->get();

        Log::info('🎯 Students loaded: ' . $students->count());

        return view('teacher.grades.task', compact('task', 'students'));
    }
    public function updateTask(Request $request, GradeTask $task)
    {
        $teacher = Auth::user()->teacher;
        
        if ($task->teacher_id !== $teacher->id) {
            abort(403, 'You do not have permission to update this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_score' => 'required|numeric|min:1|max:1000',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only(['title', 'description', 'max_score', 'due_date']));

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function storeGrade(Request $request, GradeTask $task)
    {
        Log::info('🎯 STORE GRADE METHOD CALLED');
        Log::info('Task: ' . $task->id . ' - ' . $task->title);
        Log::info('Request data:', $request->all());

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'score' => 'required|numeric|min:0|max:' . $task->max_score,
            'comments' => 'nullable|string|max:500',
        ]);

        $teacher = Auth::user()->teacher;
        
        // Verify teacher owns this task
        if ($task->teacher_id !== $teacher->id) {
            Log::warning('UNAUTHORIZED: Teacher does not own this task');
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized action'
            ], 403);
        }

        // Verify student belongs to the task's class
        $student = Student::findOrFail($request->student_id);
        $studentInClass = $task->classGroup->students()->where('students.id', $student->id)->exists();
        
        if (!$studentInClass) {
            Log::warning('STUDENT NOT IN CLASS: Student not in task class group');
            return response()->json([
                'success' => false, 
                'message' => 'Student not in this class'
            ], 400);
        }

        try {
            Log::info('Creating/updating grade entry...');
            
            $gradeEntry = GradeEntry::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'student_id' => $request->student_id,
                ],
                [
                    'score' => $request->score,
                    'comments' => $request->comments,
                    'graded_at' => now(),
                ]
            );

            Log::info('✅ GRADE SAVED SUCCESSFULLY', [
                'grade_entry_id' => $gradeEntry->id,
                'student_id' => $gradeEntry->student_id,
                'score' => $gradeEntry->score
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Grade saved successfully',
                'grade' => [
                    'id' => $gradeEntry->id,
                    'score' => $gradeEntry->score,
                    'comments' => $gradeEntry->comments,
                ],
                'percentage' => $gradeEntry->percentage,
                'grade_letter' => $gradeEntry->grade_letter,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ ERROR SAVING GRADE:', [
                'error' => $e->getMessage(),
                'task_id' => $task->id,
                'student_id' => $request->student_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving grade: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateGrade(Request $request, GradeEntry $gradeEntry)
    {
        $teacher = Auth::user()->teacher;
        
        if ($gradeEntry->task->teacher_id !== $teacher->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:' . $gradeEntry->task->max_score,
            'comments' => 'nullable|string|max:500',
        ]);

        $gradeEntry->update([
            'score' => $request->score,
            'comments' => $request->comments,
            'graded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'grade' => $gradeEntry,
            'percentage' => $gradeEntry->percentage,
            'grade_letter' => $gradeEntry->grade_letter,
        ]);
    }

    // Legacy methods for backward compatibility
    public function create()
    {
        $teacher = Auth::user()->teacher;
        
        $classGroups = $teacher->classGroups;
        $subjects = $teacher->subjects;
        
        return view('teacher.grades.create', compact('classGroups', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|numeric|min:0|max:100',
            'assignment_name' => 'required|string|max:255',
            'assignment_type' => 'required|in:homework,quiz,test,project,participation',
            'comments' => 'nullable|string|max:500',
        ]);

        $teacher = Auth::user()->teacher;

        // Verify the teacher teaches this subject
        $subject = Subject::findOrFail($validated['subject_id']);
        if ($subject->teacher_id !== $teacher->id) {
            return redirect()->back()->with('error', 'You are not assigned to teach this subject.');
        }

        // Verify student is in a class taught by this teacher
        $student = Student::findOrFail($validated['student_id']);
        $studentClass = $student->classGroups()->whereHas('subjects', function($query) use ($teacher, $subject) {
            $query->where('teacher_id', $teacher->id)
                  ->where('subject_id', $subject->id);
        })->first();

        if (!$studentClass) {
            return redirect()->back()->with('error', 'Student is not in your class for this subject.');
        }

        $validated['teacher_id'] = $teacher->id;
        $validated['date'] = now();
        $validated['max_grade'] = 100;
        
        try {
            Grade::create($validated);
            
            return redirect()->route('teacher.grades.index')
                            ->with('success', 'Grade added successfully!');
                            
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error adding grade: ' . $e->getMessage());
        }
    }

    public function show(Grade $grade)
    {
        // Verify the grade belongs to this teacher
        if ($grade->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $grade->load(['student.user', 'subject']);
        
        return view('teacher.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        // Verify the grade belongs to this teacher
        if ($grade->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $teacher = Auth::user()->teacher;
        $classGroups = $teacher->classGroups;
        $subjects = $teacher->subjects;
        
        return view('teacher.grades.edit', compact('grade', 'classGroups', 'subjects'));
    }

    public function update(Request $request, Grade $grade)
    {
        // Verify the grade belongs to this teacher
        if ($grade->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|numeric|min:0|max:100',
            'assignment_name' => 'required|string|max:255',
            'assignment_type' => 'required|in:homework,quiz,test,project,participation',
        ]);

        $grade->update($validated);
        
        return redirect()->route('teacher.grades.index')
                        ->with('success', 'Grade updated successfully!');
    }

    public function destroy(Grade $grade)
    {
        // Verify the grade belongs to this teacher
        if ($grade->teacher_id !== Auth::user()->teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $grade->delete();
        
        return redirect()->route('teacher.grades.index')
                        ->with('success', 'Grade deleted successfully!');
    }

    public function getStudentsByClass($classGroupId)
    {
        $teacher = Auth::user()->teacher;
        
        // Verify the teacher teaches this class
        $classGroup = ClassGroup::where('id', $classGroupId)
                              ->whereHas('subjects', function($query) use ($teacher) {
                                  $query->where('teacher_id', $teacher->id);
                              })
                              ->firstOrFail();
        
        $students = $classGroup->students()->with('user')->get();
        
        return response()->json($students);
    }

    private function getClassPerformanceData(ClassGroup $classGroup, Subject $subject)
    {
        $tasks = GradeTask::where('class_group_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->with(['gradeEntries' => function($query) {
                $query->whereNotNull('score');
            }])
            ->get();

        $performance = [
            'labels' => [],
            'average_scores' => [],
            'task_types' => [],
        ];

        foreach ($tasks as $task) {
            $performance['labels'][] = $task->title;
            $performance['task_types'][] = $task->type;
            
            // Calculate average score for this task
            if ($task->gradeEntries->count() > 0) {
                $totalScore = $task->gradeEntries->sum('score');
                $averageScore = $totalScore / $task->gradeEntries->count();
                $averagePercentage = ($averageScore / $task->max_score) * 100;
                $performance['average_scores'][] = round($averagePercentage, 1);
                
                Log::info("📊 Chart Data - Task: {$task->title}", [
                    'total_students_graded' => $task->gradeEntries->count(),
                    'total_score' => $totalScore,
                    'average_score' => $averageScore,
                    'average_percentage' => $averagePercentage,
                    'max_score' => $task->max_score
                ]);
            } else {
                $performance['average_scores'][] = 0;
                Log::info("📊 Chart Data - Task: {$task->title} - No grades yet");
            }
        }

        Log::info("📈 Final Chart Data", $performance);

        return $performance;
    }

    // Additional method to get student performance overview
    public function getStudentPerformance(ClassGroup $classGroup, Student $student)
    {
        $teacher = Auth::user()->teacher;
        
        // Verify the teacher teaches this class and student
        $subject = $classGroup->subjects()->where('teacher_id', $teacher->id)->first();
        if (!$subject || !$classGroup->students()->where('students.id', $student->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $tasks = GradeTask::where('class_group_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->with(['gradeEntries' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->get();

        $performanceData = [
            'labels' => [],
            'scores' => [],
            'percentages' => [],
            'task_types' => []
        ];

        foreach ($tasks as $task) {
            $performanceData['labels'][] = $task->title;
            $performanceData['task_types'][] = $task->type;
            
            $gradeEntry = $task->gradeEntries->first();
            if ($gradeEntry && $gradeEntry->score !== null) {
                $performanceData['scores'][] = $gradeEntry->score;
                $performanceData['percentages'][] = $gradeEntry->percentage;
            } else {
                $performanceData['scores'][] = null;
                $performanceData['percentages'][] = null;
            }
        }

        return response()->json($performanceData);
    }
}