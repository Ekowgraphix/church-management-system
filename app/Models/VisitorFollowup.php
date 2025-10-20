<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'followed_up_by',
        'followup_date',
        'method',
        'notes',
        'outcome',
    ];

    protected $casts = [
        'followup_date' => 'date',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function followedUpBy()
    {
        return $this->belongsTo(User::class, 'followed_up_by');
    }
}
