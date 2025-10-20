<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'equipment_code',
        'name',
        'category_id',
        'description',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'vendor',
        'location',
        'condition',
        'status',
        'warranty_expiry',
        'maintenance_interval_days',
        'last_maintenance_date',
        'next_maintenance_date',
        'image',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function assignments()
    {
        return $this->hasMany(EquipmentAssignment::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(EquipmentMaintenance::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeNeedsMaintenance($query)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->whereDate('next_maintenance_date', '<=', now()->addDays(7));
    }

    public function scopeOverdueMaintenance($query)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->whereDate('next_maintenance_date', '<', now());
    }

    /**
     * Calculate depreciation using straight-line method
     */
    public function calculateDepreciation($usefulLifeYears = 5)
    {
        if (!$this->purchase_date || !$this->purchase_price) {
            return null;
        }

        $yearsOld = now()->diffInYears($this->purchase_date);
        $annualDepreciation = $this->purchase_price / $usefulLifeYears;
        $totalDepreciation = min($annualDepreciation * $yearsOld, $this->purchase_price);
        $currentValue = $this->purchase_price - $totalDepreciation;

        return [
            'original_value' => $this->purchase_price,
            'years_old' => $yearsOld,
            'annual_depreciation' => $annualDepreciation,
            'total_depreciation' => $totalDepreciation,
            'current_value' => max($currentValue, 0),
            'depreciation_rate' => ($totalDepreciation / $this->purchase_price) * 100,
        ];
    }

    /**
     * Check if maintenance is due soon
     */
    public function isMaintenanceDue()
    {
        if (!$this->next_maintenance_date) {
            return false;
        }

        return $this->next_maintenance_date->isPast() || 
               $this->next_maintenance_date->diffInDays(now()) <= 7;
    }

    /**
     * Get maintenance status badge
     */
    public function getMaintenanceStatusAttribute()
    {
        if (!$this->next_maintenance_date) {
            return ['status' => 'none', 'label' => 'Not Scheduled', 'color' => 'gray'];
        }

        if ($this->next_maintenance_date->isPast()) {
            return ['status' => 'overdue', 'label' => 'Overdue', 'color' => 'red'];
        }

        $daysUntil = $this->next_maintenance_date->diffInDays(now());
        if ($daysUntil <= 7) {
            return ['status' => 'due_soon', 'label' => 'Due Soon', 'color' => 'yellow'];
        }

        return ['status' => 'scheduled', 'label' => 'Scheduled', 'color' => 'green'];
    }

    /**
     * Get QR code data
     */
    public function getQrCodeData()
    {
        return json_encode([
            'id' => $this->id,
            'code' => $this->equipment_code,
            'name' => $this->name,
            'category' => $this->category->name ?? 'N/A',
            'url' => route('equipment.show', $this->id),
        ]);
    }
}
