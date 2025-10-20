<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceQrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'qr_code',
        'qr_image_path',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
