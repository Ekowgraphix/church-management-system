<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PledgePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pledge_id',
        'donation_id',
        'amount',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
