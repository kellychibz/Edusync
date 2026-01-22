<?php
// database/seeders/SchoolStructureSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\GradeLevel;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassGroup;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SchoolStructureSeeder extends Seeder
{
    public function run()
    {
        echo "Starting School Structure Seeder...\n";

        // Create or Update Departments
        $departments = [
            [
                'name' => 'Mathematics Department',
                'code' => 'MATH',
                'description' => 'Mathematics and Statistics Department'
            ],
            [
                'name' => 'Science Department', 
                'code' => 'SCI',
                'description' => 'Science and Technology Department'
            ],
            [
                'name' => 'Languages Department',
                'code' => 'LANG', 
                'description' => 'Languages and Literature Department'
            ],
            [
                'name' => 'Humanities Department',
                'code' => 'HUM',
                'description' => 'Humanities and Social Sciences Department'
            ]
        ];

        foreach ($departments as $deptData) {
            Department::updateOrCreate(
                ['code' => $deptData['code']],
                $deptData
            );
        }

        echo "✅ Created/Updated departments\n";

        // Create or Update Grade Levels (8-12)
        $gradeLevels = [
            ['name' => 'Grade 8', 'level' => 8],
            ['name' => 'Grade 9', 'level' => 9],
            ['name' => 'Grade 10', 'level' => 10],
            ['name' => 'Grade 11', 'level' => 11],
            ['name' => 'Grade 12', 'level' => 12],
        ];

        foreach ($gradeLevels as $gradeData) {
            GradeLevel::updateOrCreate(
                ['level' => $gradeData['level']],
                $gradeData
            );
        }

        echo "✅ Created/Updated grade levels\n";

        // Assign Departments to Subjects
        $mathDept = Department::where('code', 'MATH')->first();
        $sciDept = Department::where('code', 'SCI')->first();
        $langDept = Department::where('code', 'LANG')->first();
        $humDept = Department::where('code', 'HUM')->first();

        if ($mathDept) Subject::where('name', 'Mathematics')->update(['department_id' => $mathDept->id]);
        if ($sciDept) Subject::where('name', 'Science')->update(['department_id' => $sciDept->id]);
        if ($langDept) Subject::where('name', 'English')->update(['department_id' => $langDept->id]);
        if ($humDept) {
            Subject::where('name', 'History')->update(['department_id' => $humDept->id]);
            Subject::where('name', 'Geography')->update(['department_id' => $humDept->id]);
        }

        echo "✅ Assigned departments to subjects\n";

        // Assign Grade Levels to Classes based on their names
        $grade10 = GradeLevel::where('level', 10)->first();
        $grade11 = GradeLevel::where('level', 11)->first();

        if ($grade10) ClassGroup::where('name', 'like', 'Grade 10%')->update(['grade_level_id' => $grade10->id]);
        if ($grade11) ClassGroup::where('name', 'like', 'Grade 11%')->update(['grade_level_id' => $grade11->id]);

        echo "✅ Assigned grade levels to classes\n";

        // Assign stream types based on class subjects
        ClassGroup::where('subject', 'Mathematics')->update(['stream_type' => 'science']);
        ClassGroup::where('subject', 'Science')->update(['stream_type' => 'science']);
        ClassGroup::where('subject', 'English')->update(['stream_type' => 'arts']);

        echo "✅ Assigned stream types to classes\n";

        // Assign teachers to departments based on their specialization
        $teachers = Teacher::with('user')->get();
        
        foreach ($teachers as $teacher) {
            if (str_contains(strtolower($teacher->specialization), 'chemistry') || 
                str_contains(strtolower($teacher->specialization), 'science')) {
                $teacher->update(['department_id' => $sciDept->id ?? null]);
            } elseif (str_contains(strtolower($teacher->specialization), 'math')) {
                $teacher->update(['department_id' => $mathDept->id ?? null]);
            } else {
                // Default to Languages department
                $teacher->update(['department_id' => $langDept->id ?? null]);
            }
        }

        echo "✅ Assigned teachers to departments\n";

        // Create some class-subject-teacher assignments
        $this->assignClassSubjects();

        echo "🎉 School structure setup completed!\n";
    }

    private function assignClassSubjects()
    {
        // Get classes, subjects, and teachers
        $classes = ClassGroup::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        // JSON schedule options that work with the json_valid constraint
        $scheduleOptions = [
            '["Monday", "Wednesday", "Friday"]',
            '["Tuesday", "Thursday"]',
            '["Monday", "Tuesday", "Wednesday"]',
            '["Wednesday", "Thursday", "Friday"]',
            '["Monday", "Friday"]',
        ];

        foreach ($classes as $class) {
            // Assign 3-4 subjects to each class
            $classSubjects = $subjects->random(min(3, $subjects->count()));
            
            foreach ($classSubjects as $subject) {
                // Find a teacher from the same department as the subject
                $suitableTeacher = $teachers->firstWhere('department_id', $subject->department_id);
                
                if ($suitableTeacher) {
                    // Use updateOrInsert to avoid duplicates
                    DB::table('class_subject')->updateOrInsert(
                        [
                            'class_id' => $class->id,
                            'subject_id' => $subject->id
                        ],
                        [
                            'teacher_id' => $suitableTeacher->id,
                            'periods_per_week' => rand(4, 8),
                            'is_core' => in_array($subject->name, ['Mathematics', 'English', 'Science']),
                            'schedule' => $scheduleOptions[array_rand($scheduleOptions)], // JSON format
                            'room_number' => 'Room ' . rand(101, 210),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }

        echo "✅ Assigned subjects to classes with teachers\n";
    }
}
