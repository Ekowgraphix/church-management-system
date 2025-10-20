<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'cluster_id',
        'followup_type_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'completed_date',
        'outcome',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function type()
    {
        return $this->belongsTo(FollowupType::class, 'followup_type_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities()
    {
        return $this->hasMany(FollowupActivity::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
            ->where('due_date', '<', now());
    }
}
