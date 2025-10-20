<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelfareFund extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'description',
        'amount',
        'approved_by',
        'category',
    ];
}
