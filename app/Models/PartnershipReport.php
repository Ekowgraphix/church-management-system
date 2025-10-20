<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnershipReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'title',
        'summary',
        'report_date',
        'file_path',
        'metrics',
    ];

    protected $casts = [
        'report_date' => 'date',
        'metrics' => 'array',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
