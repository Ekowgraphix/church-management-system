<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'transfer_type',
        'previous_church',
        'new_church',
        'pastor_name',
        'pastor_email',
        'pastor_phone',
        'request_date',
        'approval_date',
        'transfer_date',
        'status',
        'reason',
        'notes',
        'approved_by',
        'letter_of_transfer',
    ];

    protected $casts = [
        'request_date' => 'date',
        'approval_date' => 'date',
        'transfer_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
