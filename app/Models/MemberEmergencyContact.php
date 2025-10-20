<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberEmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'name',
        'relationship',
        'phone',
        'alternate_phone',
        'address',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
