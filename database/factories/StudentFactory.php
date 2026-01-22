<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ClassGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'student']),
            'class_id' => ClassGroup::factory(),
            'parent_phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-10 years'),
        ];
    }
}