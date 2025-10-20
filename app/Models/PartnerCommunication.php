<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'user_id',
        'type',
        'subject',
        'notes',
        'communication_date',
        'outcome',
    ];

    protected $casts = [
        'communication_date' => 'date',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
