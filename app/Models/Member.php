<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'email',
        'photo',
        'phone',
        'alternate_phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'profile_photo',
        'marital_status',
        'wedding_anniversary',
        'occupation',
        'employer',
        'membership_status',
        'membership_date',
        'baptism_date',
        'baptism_certificate',
        'notes',
        'custom_fields',
        'qr_code',
        'last_qr_scan',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'wedding_anniversary' => 'date',
        'membership_date' => 'date',
        'baptism_date' => 'date',
        'custom_fields' => 'array',
    ];

    // Relationships
    public function emergencyContacts()
    {
        return $this->hasMany(MemberEmergencyContact::class);
    }

    public function documents()
    {
        return $this->hasMany(MemberDocument::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(MemberStatusHistory::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function qrCode()
    {
        return $this->hasOne(AttendanceQrCode::class);
    }

    public function followups()
    {
        return $this->hasMany(Followup::class);
    }

    public function clusters()
    {
        return $this->belongsToMany(Cluster::class, 'cluster_members')
            ->withPivot('joined_date', 'left_date', 'status')
            ->withTimestamps();
    }

    public function prayerRequests()
    {
        return $this->hasMany(PrayerRequest::class);
    }

    public function onboarding()
    {
        return $this->hasOne(MemberOnboarding::class);
    }

    public function membershipClasses()
    {
        return $this->belongsToMany(MembershipClass::class, 'class_member')
            ->withPivot('enrolled_date', 'completed_date', 'status', 'attendance_count', 'notes')
            ->withTimestamps();
    }

    public function transfers()
    {
        return $this->hasMany(MemberTransfer::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('membership_status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('membership_status', 'inactive');
    }
}
