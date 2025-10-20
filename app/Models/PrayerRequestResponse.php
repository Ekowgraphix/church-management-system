<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerRequestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'prayer_request_id',
        'user_id',
        'response',
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
