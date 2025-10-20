<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_name',
        'description',
        'goal_amount',
        'raised_amount',
        'start_date',
        'end_date',
        'status',
        'campaign_image',
        'is_featured',
        'donor_count',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'campaign_id');
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->goal_amount || $this->goal_amount == 0) return 0;
        return min(100, round(($this->raised_amount / $this->goal_amount) * 100, 2));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
