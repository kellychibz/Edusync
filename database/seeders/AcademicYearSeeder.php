<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Term;
use Carbon\Carbon;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        $currentYear = AcademicYear::create([
            'year' => '2024-2025',
            'is_active' => true
        ]);

        $terms = [
            [
                'term_number' => 1,
                'name' => 'Term 1',
                'start_date' => Carbon::create(2024, 9, 1),
                'end_date' => Carbon::create(2024, 12, 15),
                'is_active' => true
            ],
            [
                'term_number' => 2,
                'name' => 'Term 2', 
                'start_date' => Carbon::create(2025, 1, 6),
                'end_date' => Carbon::create(2025, 4, 4),
                'is_active' => false
            ],
            [
                'term_number' => 3,
                'name' => 'Term 3',
                'start_date' => Carbon::create(2025, 4, 21),
                'end_date' => Carbon::create(2025, 7, 11),
                'is_active' => false
            ]
        ];

        foreach ($terms as $term) {
            $currentYear->terms()->create($term);
        }
    }
}