<?php
// database/seeders/AttendanceTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceType;

class AttendanceTypeSeeder extends Seeder
{
    public function run()
    {
        $attendanceTypes = [
            [
                'name' => 'Present',
                'code' => 'present',
                'color' => '#10b981', // green-500
                'is_present' => true,
                'counts_against_attendance' => false,
                'order' => 1,
            ],
            [
                'name' => 'Absent',
                'code' => 'absent',
                'color' => '#ef4444', // red-500
                'is_present' => false,
                'counts_against_attendance' => true,
                'order' => 2,
            ],
            [
                'name' => 'Late',
                'code' => 'late',
                'color' => '#f59e0b', // amber-500
                'is_present' => true,
                'counts_against_attendance' => false,
                'order' => 3,
            ],
            [
                'name' => 'Excused Absence',
                'code' => 'excused',
                'color' => '#8b5cf6', // violet-500
                'is_present' => false,
                'counts_against_attendance' => false,
                'order' => 4,
            ],
            [
                'name' => 'Sick',
                'code' => 'sick',
                'color' => '#6366f1', // indigo-500
                'is_present' => false,
                'counts_against_attendance' => true,
                'order' => 5,
            ],
        ];

        foreach ($attendanceTypes as $type) {
            AttendanceType::create($type);
        }
    }
}