<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization',
        'contact_person',
        'phone',
        'email',
        'start_date',
        'end_date',
        'contribution',
        'terms',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
