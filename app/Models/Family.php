<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_name',
        'head_of_family_id',
        'address',
        'city',
        'state',
        'phone',
    ];

    public function headOfFamily()
    {
        return $this->belongsTo(Member::class, 'head_of_family_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'family_members')
            ->withPivot('relationship')
            ->withTimestamps();
    }

    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }
}
