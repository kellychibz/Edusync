<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get common data for all tabs
        $gradeLevels = \App\Models\GradeLevel::where('is_active', true)->get();
        $classGroups = \App\Models\ClassGroup::all();

        // Handle different tabs
        $tab = $request->get('tab', '');

        if ($tab === 'allocation' || $tab === 'bulk-assign') {
            // For allocation tabs, we need the allocation data
            $gradeLevelsWithClasses = \App\Models\GradeLevel::with(['classes.students', 'classes.teacher.user'])
                ->where('is_active', true)
                ->get();

            $unassignedStudents = \App\Models\Student::whereDoesntHave('classGroups')->get();

            // For bulk assignment tab, we need additional data
            if ($tab === 'bulk-assign') {
                $allStudents = \App\Models\Student::with(['user', 'classGroups'])->get();
                $classGroupsGrouped = \App\Models\ClassGroup::with(['gradeLevel', 'students'])
                    ->where('is_active', true)
                    ->get()
                    ->groupBy('grade_level_id');

                return view('admin.students.index', compact(
                    'gradeLevels', 
                    'classGroups',
                    'gradeLevelsWithClasses',
                    'unassignedStudents',
                    'allStudents',
                    'classGroupsGrouped'
                ));
            }

            return view('admin.students.index', compact(
                'gradeLevels', 
                'classGroups',
                'gradeLevelsWithClasses',
                'unassignedStudents'
            ));
        }

        // Default tab: Students List
        $studentsQuery = Student::with(['user', 'classGroups.gradeLevel']);
        
        if ($request->filled('grade_level_id')) {
            $studentsQuery->whereHas('classGroups', function($q) use ($request) {
                $q->where('grade_level_id', $request->grade_level_id);
            });
        }
        
        if ($request->filled('class_group_id')) {
            $studentsQuery->whereHas('classGroups', function($q) use ($request) {
                $q->where('class_groups.id', $request->class_group_id);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('admission_number', 'like', "%{$search}%");
        }

        $students = $studentsQuery->get();

        return view('admin.students.index', compact(
            'students', 
            'gradeLevels', 
            'classGroups'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classGroups = ClassGroup::with(['gradeLevel'])
            ->withCount('students')
            ->where('is_active', true)
            ->get();
        
        $gradeLevels = \App\Models\GradeLevel::all(); // You'll need to create this model if it doesn't exist

        return view('admin.students.create', compact('classGroups', 'gradeLevels'));
    }

/**
 * Store a newly created resource in storage.
 */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'parent_phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'class_group_ids' => 'nullable|array',
            'class_group_ids.*' => 'exists:class_groups,id',
            
            // New field validations
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
        ]);

        // Generate automatic admission number
        $currentYear = date('Y');
        $nextSequence = Student::whereYear('created_at', $currentYear)->count() + 1;
        $admissionNumber = $currentYear . '/' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('student-photos', 'public');
        }

        // Create user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'student'
        ]);

        // Create student profile with admission number
        $student = Student::create([
            'user_id' => $user->id,
            'parent_phone' => $request->parent_phone,
            'date_of_birth' => $request->date_of_birth,
            'admission_number' => $admissionNumber, // Add this field to your students table
            
            // New fields
            'photo' => $photoPath,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
            'medical_conditions' => $request->medical_conditions ? explode(',', $request->medical_conditions) : null,
            'allergies' => $request->allergies ? explode(',', $request->allergies) : null,
            'blood_type' => $request->blood_type,
            'insurance_provider' => $request->insurance_provider,
            'insurance_policy_number' => $request->insurance_policy_number,
        ]);

        // Attach student to class groups with capacity validation
        if ($request->has('class_group_ids')) {
            foreach ($request->class_group_ids as $classGroupId) {
                $classGroup = ClassGroup::withCount('students')->find($classGroupId);
                
                // Check class capacity
                if ($classGroup->capacity && $classGroup->students_count >= $classGroup->capacity) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Class {$classGroup->name} has reached its capacity of {$classGroup->capacity} students.");
                }
                
                $student->classGroups()->attach($classGroupId);
            }
        }

        return redirect()->route('admin.students.index')
            ->with('success', "Student created successfully with Admission Number: {$admissionNumber}");
    }
    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['user', 'classGroups', 'grades', 'attendances']); // Changed to classGroups
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classGroups = ClassGroup::all(); // Renamed variable
        $student->load(['user', 'classGroups']); // Load classGroups relationship

        return view('admin.students.edit', compact('student', 'classGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'parent_phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'class_group_ids' => 'nullable|array', // Changed to array
            'class_group_ids.*' => 'exists:class_groups,id',
            
            // Add validation for your new fields here too:
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
        ]);

        // Update user
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        // Update student (REMOVED class_id field)
        $student->update([
            'parent_phone' => $request->parent_phone,
            'date_of_birth' => $request->date_of_birth,
            // REMOVED: 'class_id' => $request->class_id,
            
            // Update the new fields too:
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
            'medical_conditions' => $request->medical_conditions,
            'allergies' => $request->allergies,
            'blood_type' => $request->blood_type,
            'insurance_provider' => $request->insurance_provider,
            'insurance_policy_number' => $request->insurance_policy_number,
        ]);

        // Sync class groups (many-to-many)
        if ($request->has('class_group_ids')) {
            $student->classGroups()->sync($request->class_group_ids);
        } else {
            $student->classGroups()->detach();
        }

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            
            $photoPath = $request->file('photo')->store('student-photos', 'public');
            $student->update(['photo' => $photoPath]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Detach from class groups first
        $student->classGroups()->detach();
        
        // Delete the associated user
        $student->user->delete();
        
        // Delete the student
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}