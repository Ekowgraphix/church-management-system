<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CounsellingCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'color',
        'description',
        'requires_specialist',
        'session_count',
    ];

    protected $casts = [
        'requires_specialist' => 'boolean',
    ];

    /**
     * Get all sessions in this category
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(CounsellingSession::class, 'category_id');
    }

    /**
     * Increment session count
     */
    public function incrementCount()
    {
        $this->increment('session_count');
    }

    /**
     * Get icon class for display
     */
    public function getIconClass(): string
    {
        return "fas fa-{$this->icon}";
    }
}
