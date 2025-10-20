<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberOnboarding extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'stage',
        'inquiry_date',
        'first_visit_date',
        'class_enrollment_date',
        'class_completion_date',
        'baptism_date',
        'membership_date',
        'notes',
        'assigned_mentor',
        'welcome_packet_sent',
        'orientation_completed',
        'membership_covenant_signed',
    ];

    protected $casts = [
        'inquiry_date' => 'date',
        'first_visit_date' => 'date',
        'class_enrollment_date' => 'date',
        'class_completion_date' => 'date',
        'baptism_date' => 'date',
        'membership_date' => 'date',
        'welcome_packet_sent' => 'boolean',
        'orientation_completed' => 'boolean',
        'membership_covenant_signed' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function mentor()
    {
        return $this->belongsTo(Member::class, 'assigned_mentor');
    }
}
