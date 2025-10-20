<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'prayer_request_id',
        'user_id',
        'response',
        'is_praying',
    ];

    protected $casts = [
        'is_praying' => 'boolean',
    ];

    public function prayerRequest()
    {
        return $this->belongsTo(PrayerRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
