<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'name',
        'role',
        'contact',
        'email',
        'assigned_event',
        'status',
        'availability',
        'assigned_program',
        'notes',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
