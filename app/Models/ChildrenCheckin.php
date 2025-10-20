<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildrenCheckin extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'checked_in_by',
        'checked_out_by',
        'picked_up_by_guardian',
        'check_in_time',
        'check_out_time',
        'class_assigned',
        'security_code',
        'notes',
        'incident_report',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(ChildGuardian::class, 'picked_up_by_guardian');
    }
}
