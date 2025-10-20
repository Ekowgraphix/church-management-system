<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'location',
        'meeting_day',
        'meeting_time',
        'is_active',
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'cluster_members')
            ->withPivot('joined_date', 'left_date', 'status')
            ->withTimestamps();
    }

    public function followups()
    {
        return $this->hasMany(Followup::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
