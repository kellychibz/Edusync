<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'published_at',
        'expires_at',
        'priority', // high, medium, low
        'target_role' // all, teacher, student, parent
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}