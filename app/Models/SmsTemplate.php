<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'subject', 'message', 'variables', 'category', 'is_active'];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function campaigns()
    {
        return $this->hasMany(SmsCampaign::class, 'template_id');
    }
}
