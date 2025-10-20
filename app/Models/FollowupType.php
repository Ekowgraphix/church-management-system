<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowupType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'color', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function followups()
    {
        return $this->hasMany(Followup::class);
    }
}
