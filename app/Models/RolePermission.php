<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'module',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
        'can_export',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
        'can_export' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }
}
