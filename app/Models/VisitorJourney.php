<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorJourney extends Model
{
    use HasFactory;

    protected $table = 'visitor_journey';

    protected $fillable = [
        'visitor_id',
        'milestone',
        'description',
        'achieved_at',
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }
}
