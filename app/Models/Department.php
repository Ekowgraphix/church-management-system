<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
        'department_code',
        'description',
        'head_of_department',
        'status',
    ];

    public function head()
    {
        return $this->belongsTo(User::class, 'head_of_department');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
