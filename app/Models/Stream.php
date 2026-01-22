<?php
// app/Models/Stream.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code', 
        'description',
        'core_subjects',
        'optional_subjects',
        'is_active'
    ];

    protected $casts = [
        'core_subjects' => 'array',
        'optional_subjects' => 'array',
        'is_active' => 'boolean'
    ];

    public function classGroups()
    {
        return $this->hasMany(ClassGroup::class);
    }

    public function getCoreSubjectsListAttribute()
    {
        return Subject::whereIn('id', $this->core_subjects ?? [])->get();
    }

    public function getOptionalSubjectsListAttribute()
    {
        return Subject::whereIn('id', $this->optional_subjects ?? [])->get();
    }

    public function getAllSubjectsAttribute()
    {
        return Subject::whereIn('id', 
            array_merge(
                $this->core_subjects ?? [],
                $this->optional_subjects ?? []
            )
        )->get();
    }
}