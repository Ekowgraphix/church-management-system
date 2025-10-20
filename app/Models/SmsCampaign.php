<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message',
        'template_id',
        'recipient_type',
        'recipient_filters',
        'total_recipients',
        'sent_count',
        'delivered_count',
        'failed_count',
        'status',
        'scheduled_at',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'recipient_filters' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(SmsTemplate::class, 'template_id');
    }

    public function messages()
    {
        return $this->hasMany(SmsMessage::class, 'campaign_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDeliveryRateAttribute()
    {
        return $this->total_recipients > 0 
            ? ($this->delivered_count / $this->total_recipients) * 100 
            : 0;
    }
}
