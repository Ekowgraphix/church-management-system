<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'email',
        'phone',
        'address',
        'website',
        'description',
        'partnership_start_date',
        'status',
        'logo_path',
    ];

    protected $casts = [
        'partnership_start_date' => 'date',
    ];

    public function agreements(): HasMany
    {
        return $this->hasMany(PartnershipAgreement::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(PartnershipActivity::class);
    }

    public function communications(): HasMany
    {
        return $this->hasMany(PartnerCommunication::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(PartnershipReport::class);
    }

    /**
     * Get total contribution value
     */
    public function getTotalContributions()
    {
        return $this->activities()->sum('value');
    }

    /**
     * Get active agreements
     */
    public function activeAgreements()
    {
        return $this->agreements()->where('status', 'active')->get();
    }

    /**
     * Scope: Active partners
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
