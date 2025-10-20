<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsOptOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'phone_number',
        'reason',
        'opted_out_at',
    ];

    protected $casts = [
        'opted_out_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
