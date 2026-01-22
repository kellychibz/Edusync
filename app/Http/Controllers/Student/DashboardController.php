<?php
// app/Http/Controllers/Student/DashboardController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        
        // Get recent grades
        $recentGrades = $student->grades()
            ->with('subject')
            ->latest()
            ->take(5)
            ->get();
            
        // Get class group info
        $classGroup = $student->classGroups()->first();
        
        // Calculate overall average
         $overallAverage = $student->grades()->avg('grade') ?? 0;
        
        // Get grade distribution by subject - FIXED: use 'grade' instead of 'score'
        $subjectAverages = $student->grades()
            ->with('subject')
            ->selectRaw('subject_id, AVG(grade) as average, COUNT(*) as count')
            ->groupBy('subject_id')
            ->get();

        return view('dashboards.student', compact(
            'student', 
            'recentGrades', 
            'classGroup',
            'overallAverage',
            'subjectAverages'
        ));
    }
}