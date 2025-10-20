<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counselling extends Model
{
    use HasFactory;

    protected $fillable = [
        'counsellor',
        'member_id',
        'visitor_id',
        'session_date',
        'issues',
        'outcome',
        'follow_up_date',
        'confidentiality',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'datetime',
        'follow_up_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
