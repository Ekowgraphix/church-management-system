<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles as per specification
        $roles = [
            'Admin',
            'Pastor',
            'Ministry Leader',
            'Volunteer',
            'Organization',
            'Church Member'
        ];

        // Create roles
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $this->command->info('Roles seeded successfully!');
        
        // Optional: Create permissions and assign to roles
        // Define permissions
        $permissions = [
            // Dashboard
            'view_dashboard',
            
            // Members
            'view_members',
            'create_members',
            'edit_members',
            'delete_members',
            
            // Finance
            'view_finance',
            'manage_finance',
            
            // Events
            'view_events',
            'manage_events',
            
            // Reports
            'view_reports',
            'generate_reports',
            
            // Settings
            'manage_settings',
            
            // Portal
            'access_member_portal',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminRole = Role::where('name', 'Admin')->first();
        $adminRole->givePermissionTo(Permission::all()); // Admin gets all permissions

        $pastorRole = Role::where('name', 'Pastor')->first();
        $pastorRole->givePermissionTo([
            'view_dashboard',
            'view_members',
            'edit_members',
            'view_events',
            'manage_events',
            'view_reports',
        ]);

        $memberRole = Role::where('name', 'Church Member')->first();
        $memberRole->givePermissionTo([
            'access_member_portal',
        ]);

        $this->command->info('Permissions assigned successfully!');
    }
}
