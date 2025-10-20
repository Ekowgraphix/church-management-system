<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenMinistry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'child_name',
        'dob',
        'gender',
        'guardian_name',
        'guardian_contact',
        'guardian_email',
        'parent_name',
        'contact',
        'address',
        'class_group',
        'photo',
        'medical_info',
        'allergies',
        'notes',
        'registered_on',
    ];

    protected $casts = [
        'dob' => 'date',
        'registered_on' => 'date',
    ];
}
