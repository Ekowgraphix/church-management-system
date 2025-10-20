<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorFollowupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'followup_by',
        'contact_method',
        'notes',
        'voice_note_path',
        'outcome',
        'next_followup_date',
    ];

    protected $casts = [
        'next_followup_date' => 'date',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'followup_by');
    }
}
