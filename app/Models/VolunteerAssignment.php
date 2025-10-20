<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_role_id',
        'member_id',
        'assignment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(VolunteerRole::class, 'volunteer_role_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
