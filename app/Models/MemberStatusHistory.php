<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'previous_status',
        'new_status',
        'reason',
        'changed_by',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
