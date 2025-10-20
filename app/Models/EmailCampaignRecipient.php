<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaignRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_campaign_id',
        'member_id',
        'email',
        'status',
        'sent_at',
        'opened_at',
        'clicked_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'email_campaign_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
