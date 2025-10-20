<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function recipients()
    {
        return $this->hasMany(EmailCampaignRecipient::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getOpenRateAttribute()
    {
        if ($this->sent_count == 0) return 0;
        return round(($this->opened_count / $this->sent_count) * 100, 2);
    }

    public function getClickRateAttribute()
    {
        if ($this->sent_count == 0) return 0;
        return round(($this->clicked_count / $this->sent_count) * 100, 2);
    }
}
