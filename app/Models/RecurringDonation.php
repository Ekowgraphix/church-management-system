<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'donation_category_id',
        'campaign_id',
        'amount',
        'frequency',
        'start_date',
        'end_date',
        'next_payment_date',
        'status',
        'payment_method',
        'payment_token',
        'total_payments',
        'total_amount_paid',
        'last_payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_amount_paid' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_payment_date' => 'date',
        'last_payment_date' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    public function campaign()
    {
        return $this->belongsTo(DonationCampaign::class, 'campaign_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDueToday($query)
    {
        return $query->where('status', 'active')
            ->whereDate('next_payment_date', '<=', today());
    }
}
