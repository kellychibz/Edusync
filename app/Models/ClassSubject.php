<?php
// app/Models/ClassSubject.php - FIXED

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSubject extends Pivot
{
    use HasFactory;

    protected $table = 'class_subject';

    protected $fillable = [
        'class_id',        // This is the actual column name
        'subject_id',
        'teacher_id',
        'periods_per_week',
        'schedule',
        'room_number'
    ];

    protected $casts = [
        'schedule' => 'array'
    ];

    // Fix relationships - use 'class_id' not 'class_group_id'
    public function classGroup(): BelongsTo
    {
        return $this->belongsTo(ClassGroup::class, 'class_id'); // Use 'class_id'
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'class_id', 'class_id')
                    ->where('subject_id', $this->subject_id);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}