<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\Service;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Get first member and service
        $member = Member::first();
        $service = Service::first();

        if (!$member || !$service) {
            $this->command->info('No members or services found. Skipping attendance seeding.');
            return;
        }

        // Create attendance records for the past 4 weeks
        for ($i = 0; $i < 4; $i++) {
            $date = Carbon::now()->subWeeks($i)->startOfWeek();
            
            Attendance::create([
                'member_id' => $member->id,
                'service_id' => $service->id,
                'attendance_date' => $date,
                'check_in_time' => $date->copy()->setTime(9, 0),
                'check_out_time' => $date->copy()->setTime(11, 30),
                'check_in_method' => 'manual',
                'notes' => 'Sample attendance record'
            ]);
        }

        $this->command->info('Attendance records seeded successfully!');
    }
}
