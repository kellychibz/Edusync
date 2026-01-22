<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AssessmentConfig;
use App\Models\ClassGroup;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentConfigController extends Controller
{
    public function create($classGroupId, $subjectId)
    {
        $teacher = Auth::user()->teacher;
        $classGroup = ClassGroup::findOrFail($classGroupId);
        $subject = Subject::findOrFail($subjectId);
        $currentAcademicYear = AcademicYear::active()->first();
        
        // Verify the teacher teaches this class and subject
        $teacherSubject = $classGroup->subjects()->where('teacher_id', $teacher->id)->where('subject_id', $subjectId)->first();
        if (!$teacherSubject) {
            abort(403, 'You are not assigned to teach this subject in this class.');
        }
        
        return view('teacher.assessments.config-create', compact(
            'classGroup', 
            'subject', 
            'currentAcademicYear'
        ));
    }
    
    public function store(Request $request)
    {
        $userId = Auth::id();
        
        $request->validate([
            'class_group_id' => 'required|exists:class_groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'ca_percentage' => 'required|integer|min:0|max:100',
            'final_exam_percentage' => 'required|integer|min:0|max:100',
        ]);

        // Ensure percentages add up to 100
        if (($request->ca_percentage + $request->final_exam_percentage) !== 100) {
            return back()->withErrors([
                'ca_percentage' => 'CA Percentage and Final Exam Percentage must add up to 100%'
            ])->withInput();
        }

        // Use updateOrCreate to handle both new and existing configurations
        AssessmentConfig::updateOrCreate(
            [
                'class_group_id' => $request->class_group_id,
                'subject_id' => $request->subject_id,
                'academic_year_id' => $request->academic_year_id,
                'teacher_id' => $userId,
            ],
            [
                'ca_percentage' => $request->ca_percentage,
                'final_exam_percentage' => $request->final_exam_percentage,
            ]
        );

        return redirect()->route('teacher.assessments.class', $request->class_group_id)
            ->with('success', 'Assessment configuration saved successfully.');
    }
    
    public function edit(AssessmentConfig $config)
    {
        $teacher = Auth::user()->teacher;
        
        // Authorization - ensure teacher owns this config
        if ($config->teacher_id !== Auth::id()) {
            abort(403);
        }
        
        return view('teacher.assessments.config-edit', compact('config'));
    }
    
    public function update(Request $request, AssessmentConfig $config)
    {
        $teacher = Auth::user()->teacher;
        
        // Authorization
        if ($config->teacher_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'ca_percentage' => 'required|integer|min:0|max:100',
            'final_exam_percentage' => 'required|integer|min:0|max:100',
        ]);
        
        if (($request->ca_percentage + $request->final_exam_percentage) !== 100) {
            return back()->withErrors([
                'ca_percentage' => 'CA Percentage and Final Exam Percentage must add up to 100%'
            ])->withInput();
        }
        
        $config->update($request->only(['ca_percentage', 'final_exam_percentage']));
        
        return redirect()->route('teacher.assessments.class', $config->class_group_id)
            ->with('success', 'Assessment configuration updated successfully.');
    }
}