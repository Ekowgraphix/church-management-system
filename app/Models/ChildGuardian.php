<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildGuardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'name',
        'relationship',
        'phone',
        'email',
        'address',
        'can_pickup',
        'is_primary',
        'emergency_contact',
    ];

    protected $casts = [
        'can_pickup' => 'boolean',
        'is_primary' => 'boolean',
        'emergency_contact' => 'boolean',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}
