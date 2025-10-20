<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounsellingNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'original_notes',
        'ai_summary',
        'key_points',
        'action_items',
        'is_encrypted',
        'summarized_at',
    ];

    protected $casts = [
        'key_points' => 'array',
        'action_items' => 'array',
        'is_encrypted' => 'boolean',
        'summarized_at' => 'datetime',
    ];

    /**
     * Get the session for these notes
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(CounsellingSession::class);
    }

    /**
     * Generate AI summary (mock implementation)
     */
    public function generateAISummary()
    {
        // In real implementation, this would call an AI API
        $this->ai_summary = $this->mockAISummary();
        $this->key_points = $this->extractKeyPoints();
        $this->action_items = $this->extractActionItems();
        $this->summarized_at = now();
        $this->save();
    }

    /**
     * Mock AI summary generation
     */
    private function mockAISummary(): string
    {
        $notes = $this->original_notes;
        $wordCount = str_word_count($notes);
        
        return "Session summary: Discussed key issues and concerns. " .
               "Total session length: {$wordCount} words. " .
               "Main topics covered with recommended follow-up actions.";
    }

    /**
     * Extract key points from notes
     */
    private function extractKeyPoints(): array
    {
        // Mock implementation
        return [
            'Primary concern identified',
            'Current coping mechanisms discussed',
            'Support system evaluated',
            'Progress toward goals assessed',
        ];
    }

    /**
     * Extract action items
     */
    private function extractActionItems(): array
    {
        // Mock implementation
        return [
            'Schedule follow-up session',
            'Implement discussed strategies',
            'Connect with support group',
            'Review progress in 2 weeks',
        ];
    }

    /**
     * Check if notes have been summarized
     */
    public function isSummarized(): bool
    {
        return !is_null($this->ai_summary);
    }
}
