<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassGroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Grade 10A', 'Grade 10B', 'Grade 11A', 'Grade 11B', 'Grade 12A']),
            'room_number' => 'Room ' . $this->faker->numberBetween(101, 120),
            'teacher_id' => Teacher::factory(),
        ];
    }
}