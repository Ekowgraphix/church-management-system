<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Welfare extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'case_type',
        'description',
        'amount_requested',
        'amount_disbursed',
        'status',
        'handled_by',
        'notes',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_disbursed' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
