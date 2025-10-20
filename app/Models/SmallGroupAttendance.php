<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmallGroupAttendance extends Model
{
    use HasFactory;

    protected $table = 'small_group_attendance';

    protected $fillable = [
        'small_group_id',
        'member_id',
        'meeting_date',
        'check_in_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'check_in_time' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(SmallGroup::class, 'small_group_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
