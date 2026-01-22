<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function assessmentConfigs()
    {
        return $this->hasMany(AssessmentConfig::class);
    }

    public function activeTerm()
    {
        return $this->hasOne(Term::class)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}