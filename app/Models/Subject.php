<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'department_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function classGroups(): BelongsToMany
    {
        return $this->belongsToMany(ClassGroup::class, 'class_subject')
                    ->withTimestamps();
    }

        // Add this method to the Subject model
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher')
                    ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(ClassGroup::class, 'class_subject')
                    ->withPivot('schedule', 'room_number')
                    ->withTimestamps();
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}