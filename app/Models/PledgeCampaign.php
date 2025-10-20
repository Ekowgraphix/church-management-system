<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PledgeCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_name',
        'description',
        'goal_amount',
        'pledged_amount',
        'collected_amount',
        'start_date',
        'end_date',
        'status',
        'total_pledges',
        'fulfilled_pledges',
        'send_reminders',
        'reminder_frequency_days',
        'notes',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'pledged_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'send_reminders' => 'boolean',
    ];

    public function pledges()
    {
        return $this->hasMany(Pledge::class, 'campaign_id');
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->goal_amount || $this->goal_amount == 0) return 0;
        return min(100, round(($this->pledged_amount / $this->goal_amount) * 100, 2));
    }

    public function getCollectionPercentageAttribute()
    {
        if (!$this->pledged_amount || $this->pledged_amount == 0) return 0;
        return min(100, round(($this->collected_amount / $this->pledged_amount) * 100, 2));
    }

    public function getFulfillmentRateAttribute()
    {
        if (!$this->total_pledges || $this->total_pledges == 0) return 0;
        return round(($this->fulfilled_pledges / $this->total_pledges) * 100, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
