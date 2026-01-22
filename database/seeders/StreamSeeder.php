<?php
// database/seeders/StreamSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stream;
use App\Models\Subject;

class StreamSeeder extends Seeder
{
    public function run()
    {
        // Get existing subject IDs
        $math = Subject::where('name', 'like', '%Mathematics%')->first();
        $physics = Subject::where('name', 'like', '%Physics%')->first();
        $chemistry = Subject::where('name', 'like', '%Chemistry%')->first();
        $biology = Subject::where('name', 'like', '%Biology%')->first();
        $english = Subject::where('name', 'like', '%English%')->first();
        $history = Subject::where('name', 'like', '%History%')->first();
        $geography = Subject::where('name', 'like', '%Geography%')->first();

        $streams = [
            [
                'name' => 'Science',
                'code' => 'SCI',
                'description' => 'Science stream with focus on Mathematics and Sciences',
                'core_subjects' => $math && $physics && $chemistry && $biology ? 
                    [$math->id, $physics->id, $chemistry->id, $biology->id] : [1, 2, 3, 4],
                'optional_subjects' => [5, 6], // Computer, Additional Math
                'is_active' => true
            ],
            [
                'name' => 'Arts',
                'code' => 'ART', 
                'description' => 'Arts and Humanities stream',
                'core_subjects' => $math && $english && $history && $geography ? 
                    [$math->id, $english->id, $history->id, $geography->id] : [1, 7, 8, 9],
                'optional_subjects' => [10, 11], // Art, Music
                'is_active' => true
            ],
            [
                'name' => 'Technical',
                'code' => 'TEC',
                'description' => 'Technical and Vocational stream',
                'core_subjects' => $math && $physics ? 
                    [$math->id, $physics->id, 12, 13] : [1, 2, 12, 13], // Technical subjects
                'optional_subjects' => [5, 14], // Computer, Electronics
                'is_active' => true
            ]
        ];

        foreach ($streams as $stream) {
            Stream::create($stream);
        }
    }
}