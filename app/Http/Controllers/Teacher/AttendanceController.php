<?php
// app/Http\Controllers/Teacher/AttendanceController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceType;
use App\Models\ClassGroup;
use App\Models\ClassSubject;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ← ADD THIS LINE

class AttendanceController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $today = Carbon::today()->format('Y-m-d');
        
        // Get teacher's assigned classes with subjects
        $assignedClasses = ClassSubject::with(['classGroup', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->groupBy('classGroup.name');
        
        // Get today's attendance summary - FIXED: Handle empty results
        $todayAttendanceQuery = AttendanceRecord::with(['attendanceType'])
            ->where('teacher_id', $teacher->id)
            ->where('attendance_date', $today)
            ->get();

        // Initialize attendance counts
        $todayAttendance = [
            'Present' => collect(),
            'Absent' => collect(),
            'Late' => collect(),
            'Excused Absence' => collect(),
            'Sick' => collect()
        ];

        // Group by attendance type name
        foreach ($todayAttendanceQuery as $record) {
            $typeName = $record->attendanceType->name;
            if (isset($todayAttendance[$typeName])) {
                $todayAttendance[$typeName]->push($record);
            }
        }
        
        return view('teacher.attendance.index', compact('assignedClasses', 'todayAttendance', 'today'));
    }

    public function create(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('=== ATTENDANCE CREATE DEBUG START ===');
        
        $teacher = Auth::user()->teacher;
        $classId = $request->query('class_id');
        $subjectId = $request->query('subject_id');
        $date = $request->query('date', \Carbon\Carbon::today()->format('Y-m-d'));
        
        \Illuminate\Support\Facades\Log::info('Parameters received:', [
            'teacher_id' => $teacher->id,
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'date' => $date
        ]);

        // Check if class and subject exist
        $classExists = \App\Models\ClassGroup::find($classId);
        $subjectExists = \App\Models\Subject::find($subjectId);
        
        \Illuminate\Support\Facades\Log::info('Class exists: ' . ($classExists ? 'YES - ' . $classExists->name : 'NO'));
        \Illuminate\Support\Facades\Log::info('Subject exists: ' . ($subjectExists ? 'YES - ' . $subjectExists->name : 'NO'));

        if (!$classExists || !$subjectExists) {
            \Illuminate\Support\Facades\Log::error('Class or Subject not found');
            abort(404, 'Class or Subject not found.');
        }

        // Check if teacher is assigned to this class-subject combination
        $classSubject = \App\Models\ClassSubject::with(['classGroup', 'subject'])
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('teacher_id', $teacher->id)
            ->first();

        \Illuminate\Support\Facades\Log::info('ClassSubject assignment found: ' . ($classSubject ? 'YES' : 'NO'));

        if (!$classSubject) {
            \Illuminate\Support\Facades\Log::error('Teacher not assigned to this class-subject combination');
            
            // Let's see what assignments this teacher actually has
            $teacherAssignments = \App\Models\ClassSubject::with(['classGroup', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->get();
                
            \Illuminate\Support\Facades\Log::info('Teacher has ' . $teacherAssignments->count() . ' assignments:');
            foreach ($teacherAssignments as $assignment) {
                \Illuminate\Support\Facades\Log::info(' - Class: ' . $assignment->classGroup->name . ' (ID: ' . $assignment->class_id . '), Subject: ' . $assignment->subject->name . ' (ID: ' . $assignment->subject_id . ')');
            }
            
            abort(403, 'You are not assigned to teach ' . $subjectExists->name . ' in ' . $classExists->name);
        }

        \Illuminate\Support\Facades\Log::info('=== ATTENDANCE CREATE DEBUG END - SUCCESS ===');

        // Get students in this class
        $students = \App\Models\Student::with(['user'])
            ->whereHas('classGroups', function($query) use ($classId) {
                $query->where('class_groups.id', $classId);
            })
            ->get();

        \Illuminate\Support\Facades\Log::info('Students in class: ' . $students->count());

        // Get existing attendance records for this date
        $existingAttendance = \App\Models\AttendanceRecord::with(['attendanceType'])
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');

        $attendanceTypes = \App\Models\AttendanceType::ordered()->get();
        
        return view('teacher.attendance.create', compact(
            'classSubject', 
            'students', 
            'attendanceTypes', 
            'existingAttendance',
            'date'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.attendance_type_id' => 'required|exists:attendance_types,id',
            'attendance.*.notes' => 'nullable|string|max:500',
        ]);

        $teacher = Auth::user()->teacher;
        
        DB::transaction(function() use ($request, $teacher) {
            foreach ($request->attendance as $attendanceData) {
                AttendanceRecord::updateOrCreate(
                    [
                        'student_id' => $attendanceData['student_id'],
                        'class_id' => $request->class_id,
                        'subject_id' => $request->subject_id,
                        'attendance_date' => $request->attendance_date,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'attendance_type_id' => $attendanceData['attendance_type_id'],
                        'notes' => $attendanceData['notes'] ?? null,
                        'is_excused' => $attendanceData['is_excused'] ?? false,
                        'submitted_at' => now(),
                    ]
                );
            }
        });

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance recorded successfully!');
    }

    public function show(ClassGroup $classGroup, Subject $subject, $date)
    {
        $teacher = Auth::user()->teacher;
        
        // Verify the teacher is assigned to this class-subject
        $classSubject = ClassSubject::where('class_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();
        
        $attendanceRecords = AttendanceRecord::with(['student.user', 'attendanceType'])
            ->where('class_id', $classGroup->id)
            ->where('subject_id', $subject->id)
            ->where('attendance_date', $date)
            ->get();
        
        return view('teacher.attendance.show', compact('classGroup', 'subject', 'date', 'attendanceRecords'));
    }

    public function reports()
    {
        $teacher = Auth::user()->teacher;
        $month = request('month', Carbon::now()->format('Y-m'));
        
        $attendanceSummary = AttendanceRecord::with(['classGroup', 'subject', 'attendanceType'])
            ->where('teacher_id', $teacher->id)
            ->whereYear('attendance_date', Carbon::parse($month)->year)
            ->whereMonth('attendance_date', Carbon::parse($month)->month)
            ->select('class_id', 'subject_id', 'attendance_type_id', DB::raw('COUNT(*) as count'))
            ->groupBy('class_id', 'subject_id', 'attendance_type_id')
            ->get()
            ->groupBy(['classGroup.name', 'subject.name']);
        
        return view('teacher.attendance.reports', compact('attendanceSummary', 'month'));
    }
}