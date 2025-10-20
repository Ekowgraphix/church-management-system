<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'assigned_to',
        'assigned_date',
        'return_date',
        'expected_return_date',
        'purpose',
        'status',
        'return_notes',
        'assigned_by',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'return_date' => 'date',
        'expected_return_date' => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
