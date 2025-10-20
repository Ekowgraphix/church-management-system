<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Sunday Morning Service',
                'description' => 'Main Sunday worship service',
                'day_of_week' => 'sunday',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Sunday Evening Service',
                'description' => 'Evening worship and praise',
                'day_of_week' => 'sunday',
                'start_time' => '17:00:00',
                'end_time' => '19:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Wednesday Bible Study',
                'description' => 'Midweek Bible study and prayer',
                'day_of_week' => 'wednesday',
                'start_time' => '18:30:00',
                'end_time' => '20:00:00',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
            $this->command->info("Created service: {$service['name']}");
        }

        $this->command->info('Services seeded successfully!');
    }
}
