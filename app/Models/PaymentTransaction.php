<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'transaction_id',
        'payment_gateway',
        'payment_method',
        'amount',
        'currency',
        'status',
        'card_last4',
        'card_brand',
        'gateway_response',
        'receipt_url',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
