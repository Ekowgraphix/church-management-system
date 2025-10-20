<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\DonationCategory;
use App\Models\ExpenseCategory;
use App\Models\Service;
use App\Models\EquipmentCategory;
use App\Models\FollowupType;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $memberRole = Role::create(['name' => 'member']);

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@church.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create Manager User
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@church.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'is_active' => true,
        ]);
        $manager->assignRole('manager');

        // Create Donation Categories
        $donationCategories = [
            ['name' => 'Tithes', 'description' => 'Regular tithes', 'is_active' => true],
            ['name' => 'Offerings', 'description' => 'General offerings', 'is_active' => true],
            ['name' => 'Building Fund', 'description' => 'Building and construction', 'is_active' => true],
            ['name' => 'Missions', 'description' => 'Missionary support', 'is_active' => true],
            ['name' => 'Special Projects', 'description' => 'Special church projects', 'is_active' => true],
        ];
        foreach ($donationCategories as $category) {
            DonationCategory::create($category);
        }

        // Create Expense Categories
        $expenseCategories = [
            ['name' => 'Utilities', 'description' => 'Electricity, water, internet', 'is_active' => true],
            ['name' => 'Salaries', 'description' => 'Staff salaries', 'is_active' => true],
            ['name' => 'Maintenance', 'description' => 'Building and equipment maintenance', 'is_active' => true],
            ['name' => 'Supplies', 'description' => 'Office and church supplies', 'is_active' => true],
            ['name' => 'Events', 'description' => 'Church events and programs', 'is_active' => true],
        ];
        foreach ($expenseCategories as $category) {
            ExpenseCategory::create($category);
        }

        // Create Services
        $services = [
            ['name' => 'Sunday Morning Service', 'day_of_week' => 'sunday', 'start_time' => '09:00', 'end_time' => '11:00', 'is_active' => true],
            ['name' => 'Sunday Evening Service', 'day_of_week' => 'sunday', 'start_time' => '17:00', 'end_time' => '19:00', 'is_active' => true],
            ['name' => 'Wednesday Bible Study', 'day_of_week' => 'wednesday', 'start_time' => '18:00', 'end_time' => '20:00', 'is_active' => true],
            ['name' => 'Friday Prayer Meeting', 'day_of_week' => 'friday', 'start_time' => '18:00', 'end_time' => '20:00', 'is_active' => true],
        ];
        foreach ($services as $service) {
            Service::create($service);
        }

        // Create Equipment Categories
        $equipmentCategories = [
            ['name' => 'Audio/Visual', 'description' => 'Sound and video equipment', 'is_active' => true],
            ['name' => 'Musical Instruments', 'description' => 'Instruments for worship', 'is_active' => true],
            ['name' => 'Furniture', 'description' => 'Church furniture', 'is_active' => true],
            ['name' => 'Office Equipment', 'description' => 'Office supplies and equipment', 'is_active' => true],
        ];
        foreach ($equipmentCategories as $category) {
            EquipmentCategory::create($category);
        }

        // Create Follow-up Types
        $followupTypes = [
            ['name' => 'New Member', 'description' => 'Follow-up for new members', 'color' => '#22c55e', 'is_active' => true],
            ['name' => 'Visitor Follow-up', 'description' => 'Follow-up for visitors', 'color' => '#3b82f6', 'is_active' => true],
            ['name' => 'Pastoral Care', 'description' => 'Pastoral care and counseling', 'color' => '#8b5cf6', 'is_active' => true],
            ['name' => 'Sick Visit', 'description' => 'Visit to sick members', 'color' => '#ef4444', 'is_active' => true],
            ['name' => 'General Check-in', 'description' => 'General member check-in', 'color' => '#f59e0b', 'is_active' => true],
        ];
        foreach ($followupTypes as $type) {
            FollowupType::create($type);
        }

        // Create SMS Templates
        $smsTemplates = [
            [
                'name' => 'Birthday Greeting',
                'message' => 'Happy Birthday {name}! May God bless you abundantly on your special day.',
                'category' => 'birthday',
                'variables' => json_encode(['name']),
                'is_active' => true,
            ],
            [
                'name' => 'Event Reminder',
                'message' => 'Hi {name}, reminder about {event} on {date} at {time}. See you there!',
                'category' => 'event',
                'variables' => json_encode(['name', 'event', 'date', 'time']),
                'is_active' => true,
            ],
            [
                'name' => 'Welcome Message',
                'message' => 'Welcome to our church family {name}! We are glad to have you with us.',
                'category' => 'general',
                'variables' => json_encode(['name']),
                'is_active' => true,
            ],
        ];
        foreach ($smsTemplates as $template) {
            SmsTemplate::create($template);
        }

        // Create Sample Members
        for ($i = 1; $i <= 20; $i++) {
            Member::create([
                'member_id' => 'MEM-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'first_name' => 'Member',
                'last_name' => 'Test' . $i,
                'email' => 'member' . $i . '@example.com',
                'phone' => '+1234567' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'gender' => $i % 2 == 0 ? 'male' : 'female',
                'date_of_birth' => now()->subYears(rand(20, 60))->subDays(rand(1, 365)),
                'address' => $i . ' Church Street',
                'city' => 'Sample City',
                'state' => 'Sample State',
                'membership_status' => 'active',
                'membership_date' => now()->subMonths(rand(1, 24)),
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin Email: admin@church.com');
        $this->command->info('Admin Password: password');
    }
}
