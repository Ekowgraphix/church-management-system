<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'member_id',
        'donation_category_id',
        'amount',
        'donation_date',
        'payment_method',
        'reference_number',
        'is_recurring',
        'recurring_frequency',
        'recurring_end_date',
        'is_anonymous',
        'notes',
        'receipt_number',
        'receipt_sent',
        'receipt_sent_at',
        'status',
        'recorded_by',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'recurring_end_date' => 'date',
        'receipt_sent_at' => 'datetime',
        'is_recurring' => 'boolean',
        'is_anonymous' => 'boolean',
        'receipt_sent' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function pledgePayments()
    {
        return $this->hasMany(PledgePayment::class);
    }

    public function campaign()
    {
        return $this->belongsTo(DonationCampaign::class, 'campaign_id');
    }

    public function paymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('donation_date', [$startDate, $endDate]);
    }
}
