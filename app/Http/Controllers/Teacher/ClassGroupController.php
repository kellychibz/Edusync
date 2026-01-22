<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class ClassGroupController extends Controller
{
    public function show(ClassGroup $classGroup)
    {
        $teacher = Auth::user()->teacher;
        
        // Verify the teacher teaches this class
        if ($classGroup->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load class group with students and their grades
        $classGroup->load(['students.user', 'subjects']);
        
        // Get recent grades for this class
        $recentGrades = Grade::whereHas('student', function($query) use ($classGroup) {
                            $query->whereHas('classGroups', function($q) use ($classGroup) {
                                $q->where('class_groups.id', $classGroup->id);
                            });
                        })
                        ->with(['student.user', 'subject'])
                        ->latest()
                        ->take(10)
                        ->get();
        
        return view('teacher.class-groups.show', compact('classGroup', 'recentGrades'));
    }
}