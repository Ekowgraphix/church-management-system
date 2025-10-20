<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowupActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'followup_id',
        'user_id',
        'activity_date',
        'activity_type',
        'notes',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function followup()
    {
        return $this->belongsTo(Followup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
