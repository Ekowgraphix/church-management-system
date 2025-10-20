<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_name',
        'expense_category_id',
        'department_id',
        'allocated_amount',
        'spent_amount',
        'fiscal_year',
        'period',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->allocated_amount - $this->spent_amount;
    }

    public function getUtilizationPercentageAttribute()
    {
        if (!$this->allocated_amount || $this->allocated_amount == 0) return 0;
        return min(100, round(($this->spent_amount / $this->allocated_amount) * 100, 2));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
