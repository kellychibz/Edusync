<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\GradeLevel;
use App\Models\Stream;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassGroupController extends Controller
{
    public function index()
    {
        $classGroups = ClassGroup::with(['gradeLevel', 'stream', 'teacher.user', 'students'])
                                ->withCount('students')
                                ->get();
        
        $gradeLevels = GradeLevel::all();
        $streams = Stream::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.class-groups.index', compact(
            'classGroups', 
            'gradeLevels', 
            'streams', 
            'teachers'
        ));
    }

    public function create()
    {
        $gradeLevels = GradeLevel::all();
        $streams = Stream::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();

        return view('admin.class-groups.create', compact(
            'gradeLevels', 
            'streams', 
            'teachers',
            'subjects'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_code' => 'required|string|max:10',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'stream_id' => 'required|exists:streams,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $classGroup = ClassGroup::create([
            'name' => $request->name,
            'class_code' => $request->class_code,
            'grade_level_id' => $request->grade_level_id,
            'stream_id' => $request->stream_id,
            'teacher_id' => $request->teacher_id,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'academic_year' => '2425',
        ]);

        // FIX: Check if stream exists and subjects exist before assigning
        $stream = Stream::find($request->stream_id);
        if ($stream && $stream->core_subjects) {
            foreach ($stream->core_subjects as $subjectId) {
                // CHECK IF SUBJECT EXISTS BEFORE ATTACHING
                $subject = Subject::find($subjectId);
                if ($subject) {
                    $classGroup->subjects()->attach($subjectId, [
                        'periods_per_week' => 5,
                        'schedule' => json_encode([])
                    ]);
                }
            }
        }

        return redirect()->route('admin.class-groups.index')
            ->with('success', 'Class created successfully with stream subjects.');
    }

    public function show(ClassGroup $classGroup)
    {
        $classGroup->load([
            'gradeLevel', 
            'stream', 
            'teacher.user', 
            'students.user',
            'subjects',
            'classSubjects.subject.department', // Subject assignments
            'classSubjects.teacher.user'        // Teachers for each subject
            //'subjects.teacher.user'
        ]);

        return view('admin.class-groups.show', compact('classGroup'));
    }

    public function edit(ClassGroup $classGroup)
    {
        $gradeLevels = GradeLevel::all();
        $streams = Stream::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.class-groups.edit', compact(
            'classGroup',
            'gradeLevels', 
            'streams', 
            'teachers'
        ));
    }

    public function update(Request $request, ClassGroup $classGroup)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_code' => 'required|string|max:10',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'stream_id' => 'required|exists:streams,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $classGroup->update([
            'name' => $request->name,
            'class_code' => $request->class_code,
            'grade_level_id' => $request->grade_level_id,
            'stream_id' => $request->stream_id,
            'teacher_id' => $request->teacher_id,
            'capacity' => $request->capacity,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.class-groups.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassGroup $classGroup)
    {
        if ($classGroup->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete class that has students assigned.');
        }

        $classGroup->delete();

        return redirect()->route('admin.class-groups.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function manageSubjects(ClassGroup $classGroup)
    {
        $classGroup->load(['subjects', 'stream']);
        $allSubjects = Subject::all();
        $teachers = Teacher::with('user')->get();

        return view('admin.class-groups.manage-subjects', compact(
            'classGroup',
            'allSubjects',
            'teachers'
        ));
    }

    public function updateSubjects(Request $request, ClassGroup $classGroup)
    {
        $request->validate([
            'subjects' => 'sometimes|array',
        ]);

        $classGroup->subjects()->detach();

        if ($request->has('subjects')) {
            foreach ($request->subjects as $subjectId => $subjectData) {
                if (isset($subjectData['enabled']) && $subjectData['enabled'] == '1') {


                                    // ADD DEBUGGING
                \Log::info("Subject ID: {$subjectId}, Teacher ID: " . ($subjectData['teacher_id'] ?? 'null'));
           
                    $classGroup->subjects()->attach($subjectId, [
                        'teacher_id' => $subjectData['teacher_id'] ?? null,
                        'periods_per_week' => $subjectData['periods_per_week'] ?? 5,
                        'schedule' => json_encode([])
                    ]);
                }
            }
        }

        return redirect()->route('admin.class-groups.show', $classGroup)
            ->with('success', 'Class subjects updated successfully.');
    }
}