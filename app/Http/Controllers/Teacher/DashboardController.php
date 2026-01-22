<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $today = now()->format('Y-m-d');
        
        // Get ALL classes this teacher is involved with
        $classGroups = ClassGroup::where(function($query) use ($teacher) {
            // Classes where teacher is main class teacher
            $query->where('teacher_id', $teacher->id)
                // OR classes where teacher teaches subjects
                ->orWhereHas('subjects', function($subQuery) use ($teacher) {
                    $subQuery->where('teacher_id', $teacher->id);
                });
        })
        ->withCount('students')
        ->with(['gradeLevel', 'subjects' => function($query) use ($teacher) {
            // Only load subjects this teacher teaches
            $query->where('teacher_id', $teacher->id);
        }])
        ->get();

        // Get subjects taught by this teacher (for other parts of dashboard)
        $subjects = $teacher->subjects;
        
        // Get recent grades entered by this teacher
        $recentGrades = Grade::where('teacher_id', $teacher->id)
                            ->with(['student.user', 'subject'])
                            ->latest()
                            ->take(5)
                            ->get();

        // Attendance data
        $todaysAttendanceCount = $teacher->attendanceRecords()
            ->where('attendance_date', $today)
            ->count();

        $todaysPresentCount = $teacher->attendanceRecords()
            ->where('attendance_date', $today)
            ->whereHas('attendanceType', function($query) {
                $query->where('is_present', true);
            })
            ->count();

        $todaysAbsentCount = $teacher->attendanceRecords()
            ->where('attendance_date', $today)
            ->whereHas('attendanceType', function($query) {
                $query->where('is_present', false);
            })
            ->count();

        $todaysLateCount = $teacher->attendanceRecords()
            ->where('attendance_date', $today)
            ->whereHas('attendanceType', function($query) {
                $query->where('code', 'late');
            })
            ->count();
        
        return view('teacher.dashboard', compact(
            'teacher',
            'classGroups',
            'subjects',
            'recentGrades',
            'todaysAttendanceCount',
            'todaysPresentCount',
            'todaysAbsentCount',
            'todaysLateCount'
        ));
    }
}