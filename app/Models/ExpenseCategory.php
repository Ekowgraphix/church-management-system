<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'parent_id', 'budget_amount', 'is_active'];

    protected $casts = [
        'budget_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
