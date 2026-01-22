<?php
// app/Models/Department.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'head_teacher_id', 'is_active'
    ];

    // Head teacher relationship
    public function headTeacher()
    {
        return $this->belongsTo(Teacher::class, 'head_teacher_id');
    }
    // Many-to-many teachers
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_department')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

        // Get primary teachers in this department
    public function primaryTeachers()
    {
        return $this->teachers()->wherePivot('is_primary', true);
    }
}