<?php
// app/Models/GradeLevel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'level', 'description', 'is_active'
    ];

    public function classes()
    {
        return $this->hasMany(ClassGroup::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, ClassGroup::class);
    }
}