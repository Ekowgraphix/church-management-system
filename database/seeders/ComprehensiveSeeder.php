<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ComprehensiveSeeder extends Seeder
{
    /**
     * Run all seeders to populate the entire database
     */
    public function run()
    {
        $this->command->info('🚀 Starting Comprehensive Database Seeding...');
        $this->command->newLine();

        // Run all seeders in order
        $seeders = [
            ServiceSeeder::class,
            SmallGroupSeeder::class,
            EventSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            $this->command->info("Running: " . class_basename($seeder));
            $this->call($seeder);
            $this->command->newLine();
        }

        $this->command->info('✅ Comprehensive seeding completed successfully!');
        $this->command->newLine();
        
        // Display summary
        $this->displaySummary();
    }

    private function displaySummary()
    {
        $this->command->info('📊 DATABASE SUMMARY:');
        $this->command->table(
            ['Table', 'Count'],
            [
                ['Users', \App\Models\User::count()],
                ['Members', \App\Models\Member::count()],
                ['Visitors', \App\Models\Visitor::count()],
                ['Services', \App\Models\Service::count()],
                ['Attendance Records', \App\Models\AttendanceRecord::count()],
                ['Events', \App\Models\Event::count()],
                ['Small Groups', \App\Models\SmallGroup::count()],
                ['Donations', \App\Models\Donation::count()],
                ['Expenses', \App\Models\Expense::count()],
                ['Followups', \App\Models\Followup::count()],
                ['Families', \App\Models\Family::count()],
                ['Volunteers', \App\Models\VolunteerRole::count()],
            ]
        );
    }
}
