<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssessmentConfig;
use App\Models\TermAssessment;
use App\Models\ClassGroup;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // ← ADD THIS LINE

class AssessmentController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $currentAcademicYear = AcademicYear::active()->first();
        
        // Get classes assigned to this teacher with subjects (same pattern as GradeController)
        $classes = ClassGroup::with(['subjects' => function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        }])
        ->whereHas('subjects', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })
        ->withCount('students')
        ->get();

        return view('assessments.index', compact('classes', 'currentAcademicYear', 'teacher'));
    }
    
    public function showClassAssessments($classGroupId)
    {
        $userId = Auth::id(); // Get the current user ID
        $currentAcademicYear = AcademicYear::active()->first();
        
        $classGroup = ClassGroup::with(['gradeLevel', 'students'])->findOrFail($classGroupId);
        
        // Get assessment config for this class and teacher - FIXED QUERY
        $assessmentConfigs = AssessmentConfig::where('class_group_id', $classGroupId)
            ->where('academic_year_id', $currentAcademicYear->id)
            ->where('teacher_id', $userId) // Use the current user ID
            ->with('subject')
            ->get();

        // DEBUG: Let's see what we found
        Log::info("Assessment Configs Query Results:", [
            'class_group_id' => $classGroupId,
            'user_id' => $userId,
            'academic_year_id' => $currentAcademicYear->id,
            'configs_count' => $assessmentConfigs->count(),
            'config_ids' => $assessmentConfigs->pluck('id')->toArray()
        ]);
            
        // Get term assessments for this class and teacher
        $termAssessments = TermAssessment::where('class_group_id', $classGroupId)
            ->where('teacher_id', $userId)
            ->with(['term', 'subject'])
            ->get()
            ->groupBy('term_id');
            // Get term assessments for this class and teacher    'assessments.class-assessments' 
        return view('assessments.class-assessments', compact(
            'classGroup', 
            'assessmentConfigs', 
            'termAssessments',
            'currentAcademicYear'
        ));
    }
}