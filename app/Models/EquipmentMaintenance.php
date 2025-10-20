<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'maintenance_date',
        'maintenance_type',
        'description',
        'cost',
        'performed_by',
        'vendor',
        'notes',
        'next_maintenance_date',
        'recorded_by',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
