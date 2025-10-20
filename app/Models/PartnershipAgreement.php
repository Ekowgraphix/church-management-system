<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnershipAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'commitment_amount',
        'frequency',
        'document_path',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Check if agreement is active
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               ($this->end_date === null || $this->end_date >= now());
    }
}
