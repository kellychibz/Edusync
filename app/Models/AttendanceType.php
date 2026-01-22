<?php
// app/Models/AttendanceType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color',
        'is_present',
        'counts_against_attendance',
        'order',
    ];

    protected $casts = [
        'is_present' => 'boolean',
        'counts_against_attendance' => 'boolean',
    ];

    // Relationships
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('is_present', true);
    }

    public function scopeAbsent($query)
    {
        return $query->where('is_present', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}