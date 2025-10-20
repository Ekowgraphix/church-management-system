<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'description',
        'start_date',
        'end_date',
        'instructor',
        'location',
        'max_participants',
        'status',
        'requirements',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class, 'class_member')
            ->withPivot('enrolled_date', 'completed_date', 'status', 'attendance_count', 'notes')
            ->withTimestamps();
    }

    public function enrolledMembers()
    {
        return $this->members()->wherePivot('status', 'enrolled');
    }

    public function completedMembers()
    {
        return $this->members()->wherePivot('status', 'completed');
    }
}
