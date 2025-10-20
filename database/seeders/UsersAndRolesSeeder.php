<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersAndRolesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating roles and users...');

        // Create all roles
        $roles = [
            'Admin',
            'Pastor',
            'Ministry Leader',
            'Organization',
            'Volunteer',
            'Church Member',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
            $this->command->info("✓ Role created: {$roleName}");
        }

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@church.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone' => '+1234567890',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }
        $this->command->info('✓ Admin user created: admin@church.com / password');

        // Create Pastor User
        $pastor = User::firstOrCreate(
            ['email' => 'pastor@church.com'],
            [
                'name' => 'Pastor John',
                'password' => Hash::make('password'),
                'phone' => '+1234567891',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        if (!$pastor->hasRole('Pastor')) {
            $pastor->assignRole('Pastor');
        }
        $this->command->info('✓ Pastor user created: pastor@church.com / password');

        // Create Ministry Leader User
        $ministryLeader = User::firstOrCreate(
            ['email' => 'leader@church.com'],
            [
                'name' => 'Ministry Leader Sarah',
                'password' => Hash::make('password'),
                'phone' => '+1234567892',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        if (!$ministryLeader->hasRole('Ministry Leader')) {
            $ministryLeader->assignRole('Ministry Leader');
        }
        $this->command->info('✓ Ministry Leader created: leader@church.com / password');

        // Create Organization User
        $organization = User::firstOrCreate(
            ['email' => 'org@church.com'],
            [
                'name' => 'Organization Manager',
                'password' => Hash::make('password'),
                'phone' => '+1234567893',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        if (!$organization->hasRole('Organization')) {
            $organization->assignRole('Organization');
        }
        $this->command->info('✓ Organization user created: org@church.com / password');

        // Create Volunteer User
        $volunteer = User::firstOrCreate(
            ['email' => 'volunteer@church.com'],
            [
                'name' => 'Volunteer Mike',
                'password' => Hash::make('password'),
                'phone' => '+1234567894',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        if (!$volunteer->hasRole('Volunteer')) {
            $volunteer->assignRole('Volunteer');
        }
        $this->command->info('✓ Volunteer user created: volunteer@church.com / password');

        // Create Church Member User
        $churchMember = User::firstOrCreate(
            ['email' => 'member@church.com'],
            [
                'name' => 'Church Member David',
                'password' => Hash::make('password'),
                'phone' => '+1234567895',
                'is_active' => true,
                'email_verified_at' => now(), // Pre-verified for testing
            ]
        );
        if (!$churchMember->hasRole('Church Member')) {
            $churchMember->assignRole('Church Member');
        }

        // Create member profile for Church Member
        Member::firstOrCreate(
            ['email' => 'member@church.com'],
            [
                'member_id' => 'MEM-000001',
                'first_name' => 'David',
                'last_name' => 'Johnson',
                'email' => 'member@church.com',
                'phone' => '+1234567895',
                'gender' => 'male',
                'date_of_birth' => '1990-01-15',
                'address' => '123 Church Street',
                'city' => 'Sample City',
                'state' => 'Sample State',
                'membership_status' => 'active',
                'membership_date' => now(),
            ]
        );
        $this->command->info('✓ Church Member created: member@church.com / password');

        $this->command->info('');
        $this->command->info('===========================================');
        $this->command->info('✅ All users created successfully!');
        $this->command->info('===========================================');
        $this->command->info('');
        $this->command->info('Login Credentials (all passwords: password):');
        $this->command->info('');
        $this->command->info('👤 Admin:           admin@church.com');
        $this->command->info('⛪ Pastor:          pastor@church.com');
        $this->command->info('📋 Ministry Leader: leader@church.com');
        $this->command->info('🏢 Organization:    org@church.com');
        $this->command->info('🤝 Volunteer:       volunteer@church.com');
        $this->command->info('👥 Church Member:   member@church.com');
        $this->command->info('');
        $this->command->info('===========================================');
    }
}
