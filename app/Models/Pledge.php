<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pledge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pledge_number',
        'member_id',
        'campaign_name',
        'pledge_amount',
        'amount_paid',
        'pledge_date',
        'start_date',
        'end_date',
        'frequency',
        'status',
        'notes',
    ];

    protected $casts = [
        'pledge_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'pledge_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function payments()
    {
        return $this->hasMany(PledgePayment::class);
    }

    public function campaign()
    {
        return $this->belongsTo(PledgeCampaign::class, 'campaign_id');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->pledge_amount - $this->amount_paid;
    }

    public function getCompletionPercentageAttribute()
    {
        return ($this->amount_paid / $this->pledge_amount) * 100;
    }
}
