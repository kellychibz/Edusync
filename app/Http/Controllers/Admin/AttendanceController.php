<?php
// app/Http/Controllers/Admin/AttendanceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceType;
use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $classId = $request->get('class_id');
        $gradeLevelId = $request->get('grade_level_id');
        
        // Build query for attendance records
        $query = AttendanceRecord::with([
            'student.user', 
            'classGroup', 
            'subject', 
            'teacher.user',
            'attendanceType'
        ]);
        
        if ($date) {
            $query->where('attendance_date', $date);
        }
        
        if ($classId) {
            $query->where('class_id', $classId);
        }
        
        $attendanceRecords = $query->latest()->paginate(50);
        
        // Get filter data
        $classGroups = ClassGroup::with('gradeLevel')->get();
        $gradeLevels = \App\Models\GradeLevel::all();
        
        // Summary statistics
        $summary = $this->getAttendanceSummary($date, $classId, $gradeLevelId);
        
        return view('admin.attendance.index', compact(
            'attendanceRecords', 
            'classGroups', 
            'gradeLevels',
            'summary',
            'date',
            'classId',
            'gradeLevelId'
        ));
    }

    public function reports(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $classId = $request->get('class_id');
        $gradeLevelId = $request->get('grade_level_id');
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        // Get attendance summary by class and type
        $attendanceByClass = AttendanceRecord::with(['classGroup', 'attendanceType'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->when($classId, function($query) use ($classId) {
                return $query->where('class_id', $classId);
            })
            ->when($gradeLevelId, function($query) use ($gradeLevelId) {
                return $query->whereHas('classGroup', function($q) use ($gradeLevelId) {
                    $q->where('grade_level_id', $gradeLevelId);
                });
            })
            ->select('class_id', 'attendance_type_id', DB::raw('COUNT(*) as count'))
            ->groupBy('class_id', 'attendance_type_id')
            ->get()
            ->groupBy('classGroup.name');
        
        // Get student attendance rates
        $studentAttendance = Student::with(['user', 'classGroups'])
            ->when($classId, function($query) use ($classId) {
                return $query->whereHas('classGroups', function($q) use ($classId) {
                    $q->where('class_groups.id', $classId);
                });
            })
            ->withCount(['attendanceRecords as present_count' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('attendance_date', [$startDate, $endDate])
                      ->whereHas('attendanceType', function($q) {
                          $q->where('is_present', true);
                      });
            }])
            ->withCount(['attendanceRecords as total_count' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('attendance_date', [$startDate, $endDate]);
            }])
            ->having('total_count', '>', 0)
            ->orderBy('attendance_rate', 'desc')
            ->get()
            ->map(function($student) {
                $student->attendance_rate = $student->total_count > 0 
                    ? round(($student->present_count / $student->total_count) * 100, 2)
                    : 0;
                return $student;
            });
        
        $classGroups = ClassGroup::with('gradeLevel')->get();
        $gradeLevels = \App\Models\GradeLevel::all();
        
        return view('admin.attendance.reports', compact(
            'attendanceByClass',
            'studentAttendance',
            'classGroups',
            'gradeLevels',
            'month',
            'classId',
            'gradeLevelId'
        ));
    }

    public function export(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $classId = $request->get('class_id');
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $attendanceRecords = AttendanceRecord::with([
            'student.user', 
            'classGroup', 
            'subject', 
            'teacher.user',
            'attendanceType'
        ])
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->when($classId, function($query) use ($classId) {
            return $query->where('class_id', $classId);
        })
        ->get();
        
        // In a real application, you would generate CSV or Excel file here
        // For now, we'll just return a view
        return view('admin.attendance.export', compact('attendanceRecords', 'month'));
    }

    private function getAttendanceSummary($date = null, $classId = null, $gradeLevelId = null)
    {
        $query = AttendanceRecord::query();
        
        if ($date) {
            $query->where('attendance_date', $date);
        }
        
        if ($classId) {
            $query->where('class_id', $classId);
        }
        
        if ($gradeLevelId) {
            $query->whereHas('classGroup', function($q) use ($gradeLevelId) {
                $q->where('grade_level_id', $gradeLevelId);
            });
        }
        
        $totalRecords = $query->count();
        
        $presentCount = (clone $query)->whereHas('attendanceType', function($q) {
            $q->where('is_present', true);
        })->count();
        
        $absentCount = (clone $query)->whereHas('attendanceType', function($q) {
            $q->where('is_present', false);
        })->count();
        
        return [
            'total' => $totalRecords,
            'present' => $presentCount,
            'absent' => $absentCount,
            'present_percentage' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0,
            'absent_percentage' => $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100, 2) : 0,
        ];
    }
}