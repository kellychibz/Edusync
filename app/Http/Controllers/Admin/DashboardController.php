<?php
// app/Http\Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassGroup;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\AttendanceRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => ClassGroup::count(),
            'todays_attendance' => AttendanceRecord::whereDate('attendance_date', today())->count(),
            'total_departments' => Department::count(),
            'total_subjects' => Subject::count(),
            'recent_grades' => Grade::with([
                'student.user', 
                'subject', 
                'teacher.user'
            ])
                ->whereHas('student')
                ->whereHas('teacher')
                ->whereHas('subject')
                ->latest()
                ->take(5)
                ->get(),
            'recent_students' => Student::with('user')
                ->latest()
                ->take(5)
                ->get()
        ];

        return view('dashboards.admin', compact('stats'));
    }
}