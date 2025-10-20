<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'dashboard_route',
        'is_active',
        'users_count',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get role permissions
     */
    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

    /**
     * Check if role has permission
     */
    public function hasPermission($module, $action = 'view')
    {
        $permission = $this->rolePermissions()
            ->where('module', $module)
            ->first();

        if (!$permission) {
            return false;
        }

        return match($action) {
            'view' => $permission->can_view,
            'create' => $permission->can_create,
            'edit' => $permission->can_edit,
            'delete' => $permission->can_delete,
            'export' => $permission->can_export,
            default => false,
        };
    }
}
