<?php
// database/seeders/DepartmentSeeder.php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Languages', 'code' => 'LANG', 'description' => 'Language and Literature Department'],
            ['name' => 'Mathematics', 'code' => 'MATH', 'description' => 'Mathematics and Statistics Department'],
            ['name' => 'Sciences', 'code' => 'SCI', 'description' => 'Science Department'],
            ['name' => 'Humanities', 'code' => 'HUM', 'description' => 'Humanities and Social Sciences Department'],
            ['name' => 'Business & IT', 'code' => 'BUSIT', 'description' => 'Business Studies and Information Technology'],
            ['name' => 'Creative Arts', 'code' => 'ARTS', 'description' => 'Creative and Performing Arts Department'],
            ['name' => 'Technical & Vocational', 'code' => 'TECH', 'description' => 'Technical and Vocational Subjects'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}