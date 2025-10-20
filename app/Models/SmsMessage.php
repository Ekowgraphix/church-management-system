<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'member_id',
        'phone_number',
        'message',
        'status',
        'provider_message_id',
        'error_message',
        'sent_at',
        'delivered_at',
        'cost',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cost' => 'decimal:4',
    ];

    public function campaign()
    {
        return $this->belongsTo(SmsCampaign::class, 'campaign_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
