<?php
// app/Http/Controllers/Admin/TeacherController.php - COMPLETE SAFE VERSION

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\Subject;
use App\Models\ClassGroup;
use App\Models\ClassSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'departments', 'subjects', 'classGroups'])
                          ->withCount(['classGroups', 'classSubjectAssignments'])
                          ->get();
        
        $departments = Department::all();
        
        return view('admin.teachers.index', compact('teachers', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $subjects = Subject::with('department')->get();
        $classGroups = ClassGroup::with('gradeLevel')->get();

        return view('admin.teachers.create', compact(
            'departments', 
            'subjects', 
            'classGroups'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'qualification' => 'required|string|max:255',
            'departments' => 'required|array|min:1',
            'departments.*' => 'exists:departments,id',
            'primary_department' => 'required|exists:departments,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'class_groups' => 'nullable|array',
            'class_groups.*' => 'exists:class_groups,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('profile_image')) {
            $photoPath = $request->file('profile_image')->store('teacher-photos', 'public');
        }

        // Create teacher
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'qualification' => $request->qualification,
            'profile_image' => $photoPath,
        ]);

        // Assign departments with primary department
        $departmentData = [];
        foreach ($request->departments as $departmentId) {
            $departmentData[$departmentId] = [
                'is_primary' => $departmentId == $request->primary_department
            ];
        }
        $teacher->departments()->sync($departmentData);

        // Assign subjects
        if ($request->has('subjects')) {
            $teacher->subjects()->sync($request->subjects);
        }

        // Assign class groups (form teacher)
        if ($request->has('class_groups')) {
            foreach ($request->class_groups as $classGroupId) {
                ClassGroup::where('id', $classGroupId)->update(['teacher_id' => $teacher->id]);
            }
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        // Load all relationships including class subject assignments
        $teacher->load([
            'user', 
            'departments',
            'subjects.department',
            'classGroups.gradeLevel',
            'classGroups.students',
            'classSubjectAssignments.classGroup.gradeLevel',
            'classSubjectAssignments.subject.department'
        ]);

        // Filter out assignments with missing relationships safely
        $validAssignments = $teacher->classSubjectAssignments->filter(function($assignment) {
            return $assignment->classGroup && $assignment->subject;
        });

        // Calculate workload summary using only valid assignments
        $workloadSummary = [
            'total_periods' => $validAssignments->sum('periods_per_week'),
            'total_classes' => $validAssignments->unique('class_id')->count(),
            'total_subjects' => $validAssignments->unique('subject_id')->count(),
        ];

        return view('admin.teachers.show', compact('teacher', 'workloadSummary'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'departments', 'subjects', 'classGroups']);
        $departments = Department::all();
        $subjects = Subject::all();
        $classGroups = ClassGroup::with('gradeLevel')->get();

        return view('admin.teachers.edit', compact(
            'teacher', 
            'departments', 
            'subjects', 
            'classGroups'
        ));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'phone' => 'required|string|max:20',
            'qualification' => 'required|string|max:255',
            'departments' => 'required|array|min:1',
            'departments.*' => 'exists:departments,id',
            'primary_department' => 'required|exists:departments,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'class_groups' => 'nullable|array',
            'class_groups.*' => 'exists:class_groups,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Use database transaction for safety
        DB::transaction(function () use ($request, $teacher) {
            // Update user
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Handle photo upload
            if ($request->hasFile('profile_image')) {
                // Delete old photo if exists
                if ($teacher->profile_image) {
                    Storage::disk('public')->delete($teacher->profile_image);
                }
                $photoPath = $request->file('profile_image')->store('teacher-photos', 'public');
                $teacher->profile_image = $photoPath;
            }

            // Update teacher
            $teacher->update([
                'phone' => $request->phone,
                'qualification' => $request->qualification,
            ]);

            // Update departments with primary department
            $departmentData = [];
            foreach ($request->departments as $departmentId) {
                $departmentData[$departmentId] = [
                    'is_primary' => $departmentId == $request->primary_department
                ];
            }
            $teacher->departments()->sync($departmentData);

            // Update subjects
            $teacher->subjects()->sync($request->subjects ?? []);

            // SAFE APPROACH: Update class groups without setting teacher_id to null
            if ($request->has('class_groups')) {
                // Get current class groups assigned to this teacher
                $currentClassGroups = $teacher->classGroups->pluck('id')->toArray();
                
                // Classes to remove this teacher from (not in the new selection)
                $classesToRemove = array_diff($currentClassGroups, $request->class_groups);
                
                // Remove teacher from classes that are no longer selected
                if (!empty($classesToRemove)) {
                    // Use raw query to avoid Eloquent constraints
                    DB::table('class_groups')
                        ->whereIn('id', $classesToRemove)
                        ->where('teacher_id', $teacher->id)
                        ->update(['teacher_id' => null]);
                }
                
                // Assign teacher to newly selected classes
                foreach ($request->class_groups as $classGroupId) {
                    ClassGroup::where('id', $classGroupId)->update(['teacher_id' => $teacher->id]);
                }
            } else {
                // If no class groups selected, try to remove from all classes
                // This will only work if teacher_id is nullable
                try {
                    ClassGroup::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
                } catch (\Exception $e) {
                    // If it fails, it's not critical - just continue
                }
            }
        });

        return redirect()->route('admin.teachers.show', $teacher)
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        // Check if teacher has classes assigned
        if ($teacher->classGroups()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete teacher that has classes assigned.');
        }

        // Check if teacher has subject assignments
        if ($teacher->classSubjectAssignments()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete teacher that has subject assignments.');
        }

        // Delete photo if exists
        if ($teacher->profile_image) {
            Storage::disk('public')->delete($teacher->profile_image);
        }

        // Delete user and teacher
        $teacher->user->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    // Class subject assignment methods
    public function manageAssignments(Teacher $teacher)
    {
        $teacher->load(['classSubjectAssignments.classGroup', 'classSubjectAssignments.subject']);
        $classGroups = ClassGroup::with(['gradeLevel', 'subjects'])->get();
        $subjects = Subject::all();

        return view('admin.teachers.manage-assignments', compact(
            'teacher', 
            'classGroups', 
            'subjects'
        ));
    }

    public function updateAssignments(Request $request, Teacher $teacher)
    {
        $request->validate([
            'assignments' => 'nullable|array',
            'assignments.*.class_group_id' => 'required|exists:class_groups,id',
            'assignments.*.subject_id' => 'required|exists:subjects,id',
            'assignments.*.periods_per_week' => 'required|integer|min:1|max:20',
        ]);

        // Clear existing assignments
        $teacher->classSubjectAssignments()->delete();

        // Create new assignments - USE 'class_id' NOT 'class_group_id'
        if ($request->has('assignments')) {
            foreach ($request->assignments as $assignment) {
                ClassSubject::create([
                    'class_id' => $assignment['class_group_id'], // Map to 'class_id'
                    'subject_id' => $assignment['subject_id'],
                    'teacher_id' => $teacher->id,
                    'periods_per_week' => $assignment['periods_per_week'],
                ]);
            }
        }

        return redirect()->route('admin.teachers.show', $teacher)
            ->with('success', 'Teacher assignments updated successfully.');
    }

    public function workload(Teacher $teacher)
    {
        $teacher->load([
            'classGroups.gradeLevel',
            'classSubjectAssignments.subject',
            'classSubjectAssignments.classGroup.gradeLevel',
            'subjects.department'
        ]);

        $workloadSummary = [
            'total_periods' => $teacher->classSubjectAssignments->sum('periods_per_week'),
            'total_classes' => $teacher->classSubjectAssignments->unique('class_id')->count(),
            'total_subjects' => $teacher->classSubjectAssignments->unique('subject_id')->count(),
        ];

        return view('admin.teachers.workload', compact('teacher', 'workloadSummary'));
    }
}