<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {
        // Get the authenticated student
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        // Load grades with subject relationship only (no classGroup)
        $grades = $student->grades()
            ->with(['subject']) // Only load subject, not classGroup
            ->orderBy('date', 'desc')
            ->get();

        // Group grades by subject
        $gradesBySubject = $grades->groupBy('subject_id');

        // Calculate statistics
        $overallAverage = $grades->avg('percentage');
        $totalAssignments = $grades->count();
        $recentGrades = $grades->take(5);

        return view('student.grades.index', compact(
            'student',
            'grades',
            'gradesBySubject',
            'overallAverage',
            'totalAssignments',
            'recentGrades'
        ));
    }

    public function bySubject($subjectId)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        $grades = $student->grades()
            ->where('subject_id', $subjectId)
            ->with(['subject']) // Only load subject, not classGroup
            ->orderBy('date', 'desc')
            ->get();

        $subject = $grades->first()->subject ?? null;
        $subjectAverage = $grades->avg('percentage');

        return view('student.grades.by-subject', compact(
            'student',
            'grades',
            'subject',
            'subjectAverage'
        ));
    }

    // Remove the byClass method since we don't have class groups in grades
    // public function byClass($classGroupId)
    // {
    //     // This method is not needed since grades don't have class_group_id
    // }
}