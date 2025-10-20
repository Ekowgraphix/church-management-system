<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tithe extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'date',
        'amount',
        'payment_method',
        'received_by',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('date', now()->year);
    }
}
