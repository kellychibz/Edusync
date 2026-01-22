<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\GradeLevel;
use Illuminate\Http\Request;

class ClassAllocationController extends Controller
{
    /**
     * Display class allocation dashboard
     */
    public function index()
    {
        $gradeLevels = GradeLevel::with(['classes.students', 'classes.teacher.user'])
            ->where('is_active', true)
            ->get();

        $unassignedStudents = Student::whereDoesntHave('classGroups')->get();

        return view('admin.class-allocations.index', compact('gradeLevels', 'unassignedStudents'));
    }

    /**
     * Show form for bulk class assignment
     */
    public function create()
    {
        $students = Student::with(['user', 'classGroups'])->get();
        $classGroups = ClassGroup::with(['gradeLevel', 'students'])
            ->where('is_active', true)
            ->get()
            ->groupBy('grade_level_id');

        $gradeLevels = GradeLevel::where('is_active', true)->get();

        return view('admin.class-allocations.bulk-assign', compact('students', 'classGroups', 'gradeLevels'));
    }

    /**
     * Process bulk class assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'class_group_ids' => 'required|array',
            'class_group_ids.*' => 'exists:class_groups,id',
            'operation' => 'required|in:assign,transfer,remove'
        ]);

        $studentIds = $request->student_ids;
        $classGroupIds = $request->class_group_ids;
        $operation = $request->operation;

        try {
            foreach ($studentIds as $studentId) {
                $student = Student::find($studentId);

                switch ($operation) {
                    case 'assign':
                        // Add to classes (without removing existing)
                        $student->classGroups()->syncWithoutDetaching($classGroupIds);
                        break;

                    case 'transfer':
                        // Replace all classes with new ones
                        $student->classGroups()->sync($classGroupIds);
                        break;

                    case 'remove':
                        // Remove from specified classes
                        $student->classGroups()->detach($classGroupIds);
                        break;
                }
            }

            $message = match($operation) {
                'assign' => 'Students successfully assigned to classes',
                'transfer' => 'Students successfully transferred to new classes',
                'remove' => 'Students successfully removed from classes',
            };

            return redirect()->route('admin.class-allocations.index')
                ->with('success', $message . ' (' . count($studentIds) . ' students affected)');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error performing operation: ' . $e->getMessage());
        }
    }

    /**
     * Show class details with students
     */
    public function show(ClassGroup $classGroup)
    {
        $classGroup->load(['students.user', 'gradeLevel', 'teacher.user']);
        
        $availableStudents = Student::whereDoesntHave('classGroups', function($query) use ($classGroup) {
            $query->where('class_groups.id', $classGroup->id);
        })->with('user')->get();

        return view('admin.class-allocations.show', compact('classGroup', 'availableStudents'));
    }

    /**
     * Add student to class
     */
    public function addStudent(Request $request, ClassGroup $classGroup)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);

        // Check capacity
        if ($classGroup->capacity && $classGroup->students()->count() >= $classGroup->capacity) {
            return redirect()->back()
                ->with('error', 'Class has reached its capacity of ' . $classGroup->capacity . ' students.');
        }

        $classGroup->students()->attach($request->student_id);

        return redirect()->back()
            ->with('success', 'Student added to class successfully.');
    }

    /**
     * Get dashboard data for AJAX loading
     */
    public function dashboardData()
    {
        $gradeLevelsWithClasses = \App\Models\GradeLevel::with(['classes.students', 'classes.teacher.user'])
            ->where('is_active', true)
            ->get();
        
        $unassignedStudents = \App\Models\Student::whereDoesntHave('classGroups')->get();
        
        return view('admin.students.partials.class-allocation-content', compact(
            'gradeLevelsWithClasses',
            'unassignedStudents'
        ));
    }

    /**
     * Remove student from class
     */
    public function removeStudent(Request $request, ClassGroup $classGroup, Student $student)
    {
        $classGroup->students()->detach($student->id);

        return redirect()->back()
            ->with('success', 'Student removed from class successfully.');
    }
}