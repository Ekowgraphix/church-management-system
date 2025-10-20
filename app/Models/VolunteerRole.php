<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'department',
        'required_volunteers',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function assignments()
    {
        return $this->hasMany(VolunteerAssignment::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(Member::class, 'volunteer_assignments')
            ->withPivot('assignment_date', 'start_time', 'end_time', 'status', 'notes')
            ->withTimestamps();
    }
}
