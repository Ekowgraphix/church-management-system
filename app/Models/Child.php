<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'medical_info',
        'allergies',
        'special_needs',
        'emergency_notes',
        'family_id',
        'photo_path',
        'qr_code',
        'photo_consent',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'photo_consent' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['full_name', 'age'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'family_id');
    }

    public function guardians(): HasMany
    {
        return $this->hasMany(ChildGuardian::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(ChildrenCheckin::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ChildrenAttendance::class);
    }

    /**
     * Generate QR code for quick check-in
     */
    public function generateQRCode()
    {
        $this->qr_code = 'CHD-' . strtoupper(substr(md5($this->id . time()), 0, 8));
        $this->save();
        return $this->qr_code;
    }

    /**
     * Check in child
     */
    public function checkIn($classId = null, $notes = null)
    {
        $securityCode = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
        
        return $this->checkins()->create([
            'checked_in_by' => auth()->id(),
            'check_in_time' => now(),
            'class_assigned' => $classId,
            'security_code' => $securityCode,
            'notes' => $notes,
        ]);
    }

    /**
     * Check out child
     */
    public function checkOut($checkinId, $guardianId)
    {
        $checkin = $this->checkins()->find($checkinId);
        if ($checkin) {
            $checkin->update([
                'checked_out_by' => auth()->id(),
                'picked_up_by_guardian' => $guardianId,
                'check_out_time' => now(),
            ]);
        }
        return $checkin;
    }

    /**
     * Get current check-in
     */
    public function currentCheckin()
    {
        return $this->checkins()->whereNull('check_out_time')->latest()->first();
    }

    /**
     * Is currently checked in
     */
    public function isCheckedIn()
    {
        return $this->currentCheckin() !== null;
    }
}
