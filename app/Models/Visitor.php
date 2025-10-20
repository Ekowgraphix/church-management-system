<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'visit_date',
        'service_attended',
        'visit_type',
        'invited_by',
        'interests',
        'prayer_requests',
        'wants_followup',
        'followup_status',
        'followup_date',
        'followup_notes',
        'converted_to_member',
        'member_id',
        'notes',
        'qr_code',
        'feedback',
        'assigned_to',
        'conversion_status',
        'visit_count',
        'last_contact_date',
        'next_followup_date',
        'sms_sent',
        'email_sent',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'followup_date' => 'date',
        'wants_followup' => 'boolean',
        'converted_to_member' => 'boolean',
        'last_contact_date' => 'date',
        'next_followup_date' => 'date',
        'sms_sent' => 'boolean',
        'email_sent' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function followups()
    {
        return $this->hasMany(VisitorFollowup::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopePendingFollowup($query)
    {
        return $query->where('followup_status', 'pending')
            ->where('wants_followup', true);
    }

    /**
     * Get visitor attendance
     */
    public function attendances()
    {
        return $this->hasMany(VisitorAttendance::class);
    }

    /**
     * Get follow-up logs
     */
    public function followupLogs()
    {
        return $this->hasMany(VisitorFollowupLog::class);
    }

    /**
     * Get journey milestones
     */
    public function journeyMilestones()
    {
        return $this->hasMany(VisitorJourney::class);
    }

    /**
     * Generate QR code
     */
    public function generateQRCode()
    {
        $this->qr_code = 'VIS-' . strtoupper(substr(md5($this->id . time()), 0, 8));
        $this->save();
        return $this->qr_code;
    }

    /**
     * Record attendance
     */
    public function recordAttendance($serviceName, $method = 'manual')
    {
        $this->attendances()->create([
            'service_name' => $serviceName,
            'attendance_date' => now(),
            'check_in_time' => now(),
            'check_in_method' => $method,
        ]);

        $this->increment('visit_count');
        $this->addJourneyMilestone("attended_{$serviceName}", "Attended {$serviceName}");

        // Auto-convert after 3 visits
        if ($this->visit_count >= 3 && $this->conversion_status === 'visitor') {
            $this->update(['conversion_status' => 'returning']);
            $this->addJourneyMilestone('returning_visitor', 'Became a returning visitor');
        }
    }

    /**
     * Add journey milestone
     */
    public function addJourneyMilestone($milestone, $description = null)
    {
        return $this->journeyMilestones()->create([
            'milestone' => $milestone,
            'description' => $description,
            'achieved_at' => now(),
        ]);
    }

    /**
     * Log follow-up
     */
    public function logFollowup($contactMethod, $notes, $outcome, $nextDate = null)
    {
        $this->followupLogs()->create([
            'followup_by' => auth()->id(),
            'contact_method' => $contactMethod,
            'notes' => $notes,
            'outcome' => $outcome,
            'next_followup_date' => $nextDate,
        ]);

        $this->update([
            'last_contact_date' => now(),
            'next_followup_date' => $nextDate,
            'followup_status' => $outcome === 'converted' ? 'Converted' : 'Completed',
        ]);
    }

    /**
     * Send welcome message
     */
    public function sendWelcomeMessage($type = 'both')
    {
        // Mock implementation - integrate with your SMS/Email service
        if (in_array($type, ['sms', 'both']) && $this->phone) {
            $this->update(['sms_sent' => true]);
        }

        if (in_array($type, ['email', 'both']) && $this->email) {
            $this->update(['email_sent' => true]);
        }

        $this->addJourneyMilestone('welcome_sent', 'Welcome message sent');
    }

    /**
     * Scope: Recent visitors
     */
    public function scopeRecent($query)
    {
        return $query->where('visit_date', '>=', now()->subDays(30));
    }

    /**
     * Scope: Converted
     */
    public function scopeConverted($query)
    {
        return $query->where('conversion_status', 'converted');
    }
}
