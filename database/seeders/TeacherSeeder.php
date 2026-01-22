<?php
// database/seeders/TeacherSeeder.php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $teachers = [
            // Languages Department Teachers
            [
                'user' => [
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.johnson@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345678',
                    'qualification' => 'M.A. in English Literature',
                    'specialization' => 'English Language and Literature',
                    'years_of_experience' => 8,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(3),
                ],
                'department_code' => 'LANG',
                'subjects' => ['ENG', 'ENG_LIT'],
            ],
            [
                'user' => [
                    'name' => 'Michael Chengo',
                    'email' => 'michael.chengo@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345679',
                    'qualification' => 'B.Ed. in Languages',
                    'specialization' => 'Kiswahili and French',
                    'years_of_experience' => 5,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(2),
                ],
                'department_code' => 'LANG',
                'subjects' => ['KIS', 'FRE'],
            ],

            // Mathematics Department Teachers
            [
                'user' => [
                    'name' => 'Dr. Robert Mwamba',
                    'email' => 'robert.mwamba@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345680',
                    'qualification' => 'Ph.D. in Mathematics',
                    'specialization' => 'Pure Mathematics',
                    'years_of_experience' => 12,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(8),
                ],
                'department_code' => 'MATH',
                'subjects' => ['MATH', 'PURE_MATH', 'ADD_MATH'],
            ],
            [
                'user' => [
                    'name' => 'Grace Odhiambo',
                    'email' => 'grace.odhiambo@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345681',
                    'qualification' => 'M.Sc. in Statistics',
                    'specialization' => 'Statistics and Applied Mathematics',
                    'years_of_experience' => 6,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(4),
                ],
                'department_code' => 'MATH',
                'subjects' => ['STAT', 'APP_MATH'],
            ],

            // Sciences Department Teachers
            [
                'user' => [
                    'name' => 'Dr. Amina Said',
                    'email' => 'amina.said@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345682',
                    'qualification' => 'Ph.D. in Physics',
                    'specialization' => 'Theoretical Physics',
                    'years_of_experience' => 10,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(6),
                ],
                'department_code' => 'SCI',
                'subjects' => ['PHY', 'GEN_SCI'],
            ],
            [
                'user' => [
                    'name' => 'David Kimani',
                    'email' => 'david.kimani@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345683',
                    'qualification' => 'M.Sc. in Chemistry',
                    'specialization' => 'Organic Chemistry',
                    'years_of_experience' => 7,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(5),
                ],
                'department_code' => 'SCI',
                'subjects' => ['CHEM', 'ENV_SCI'],
            ],
            [
                'user' => [
                    'name' => 'Linda Mbeki',
                    'email' => 'linda.mbeki@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345684',
                    'qualification' => 'M.Sc. in Biology',
                    'specialization' => 'Molecular Biology',
                    'years_of_experience' => 8,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(5),
                ],
                'department_code' => 'SCI',
                'subjects' => ['BIO', 'HUM_BIO'],
            ],

            // Humanities Department Teachers
            [
                'user' => [
                    'name' => 'Professor James Okoth',
                    'email' => 'james.okoth@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345685',
                    'qualification' => 'Ph.D. in History',
                    'specialization' => 'African History',
                    'years_of_experience' => 15,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(10),
                ],
                'department_code' => 'HUM',
                'subjects' => ['HIST', 'SOC_STUD'],
            ],
            [
                'user' => [
                    'name' => 'Mary Wambui',
                    'email' => 'mary.wambui@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345686',
                    'qualification' => 'M.A. in Geography',
                    'specialization' => 'Physical Geography',
                    'years_of_experience' => 9,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(7),
                ],
                'department_code' => 'HUM',
                'subjects' => ['GEO', 'CIV'],
            ],

            // Business & IT Department Teachers
            [
                'user' => [
                    'name' => 'Brian Omondi',
                    'email' => 'brian.omondi@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345687',
                    'qualification' => 'MBA, B.Sc. Computer Science',
                    'specialization' => 'Business and IT',
                    'years_of_experience' => 8,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(6),
                ],
                'department_code' => 'BUSIT',
                'subjects' => ['BUS', 'COMP', 'IT'],
            ],
            [
                'user' => [
                    'name' => 'Susan Njeru',
                    'email' => 'susan.njeru@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345688',
                    'qualification' => 'B.Com, CPA',
                    'specialization' => 'Accounting and Finance',
                    'years_of_experience' => 6,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(4),
                ],
                'department_code' => 'BUSIT',
                'subjects' => ['ACC', 'ECON', 'BKEEP'],
            ],

            // Creative Arts Department Teachers
            [
                'user' => [
                    'name' => 'Grace Mwangi',
                    'email' => 'grace.mwangi@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345689',
                    'qualification' => 'B.F.A. in Fine Arts',
                    'specialization' => 'Visual Arts',
                    'years_of_experience' => 5,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(3),
                ],
                'department_code' => 'ARTS',
                'subjects' => ['ART', 'DRAMA'],
            ],
            [
                'user' => [
                    'name' => 'Peter Kamau',
                    'email' => 'peter.kamau@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345690',
                    'qualification' => 'B.Mus. in Music',
                    'specialization' => 'Music Theory and Performance',
                    'years_of_experience' => 7,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(5),
                ],
                'department_code' => 'ARTS',
                'subjects' => ['MUSIC', 'PE'],
            ],

            // Technical & Vocational Department Teachers
            [
                'user' => [
                    'name' => 'John Kipchoge',
                    'email' => 'john.kipchoge@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345691',
                    'qualification' => 'B.Sc. in Agricultural Engineering',
                    'specialization' => 'Agriculture and Technical Drawing',
                    'years_of_experience' => 10,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(7),
                ],
                'department_code' => 'TECH',
                'subjects' => ['AGRIC', 'TECH_DRAW'],
            ],
            [
                'user' => [
                    'name' => 'Rebecca Achieng',
                    'email' => 'rebecca.achieng@school.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ],
                'teacher' => [
                    'phone' => '+255712345692',
                    'qualification' => 'B.Ed. in Home Economics',
                    'specialization' => 'Home Science and Nutrition',
                    'years_of_experience' => 8,
                    'profile_image' => null,
                    'hire_date' => Carbon::now()->subYears(6),
                ],
                'department_code' => 'TECH',
                'subjects' => ['HOME_SCI', 'FOOD_NUT', 'FASHION'],
            ],
        ];

        foreach ($teachers as $teacherData) {
            // Create user
            $user = User::firstOrCreate(
                ['email' => $teacherData['user']['email']],
                $teacherData['user']
            );

            // Get department
            $department = Department::where('code', $teacherData['department_code'])->first();

            // Create teacher
            $teacher = Teacher::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($teacherData['teacher'], ['department_id' => $department->id])
            );

            // Assign subjects to teacher
            $subjects = Subject::whereIn('code', $teacherData['subjects'])->get();
            $teacher->subjects()->sync($subjects->pluck('id'));
        }

        echo "Created " . count($teachers) . " teachers with subject assignments!\n";
    }
}