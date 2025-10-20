<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devotional extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'devotional_date',
        'scripture_reference',
        'scripture_text',
        'message',
        'prayer',
        'reflection_questions',
        'author',
        'is_published',
    ];

    protected $casts = [
        'devotional_date' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * Scope for published devotionals
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for today's devotional
     */
    public function scopeToday($query)
    {
        return $query->where('devotional_date', today());
    }

    /**
     * Get devotional for a specific date
     */
    public static function getForDate($date)
    {
        return self::published()
            ->where('devotional_date', $date)
            ->first();
    }
}
