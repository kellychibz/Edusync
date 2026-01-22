<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'teacher']),
            'phone' => $this->faker->phoneNumber(),
            'qualification' => $this->faker->jobTitle(),
        ];
    }
}