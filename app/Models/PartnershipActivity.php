<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnershipActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'agreement_id',
        'type',
        'title',
        'description',
        'value',
        'activity_date',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(PartnershipAgreement::class, 'agreement_id');
    }
}
