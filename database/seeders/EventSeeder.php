<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        // Get first user as creator
        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found. Creating default admin user...');
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@church.com',
                'password' => bcrypt('password'),
            ]);
        }

        $events = [
            [
                'title' => 'Sunday Worship Service',
                'description' => 'Join us for our weekly Sunday worship service with praise, worship, and powerful preaching.',
                'event_type' => 'service',
                'start_date' => Carbon::now()->next('Sunday')->setTime(9, 0),
                'end_date' => Carbon::now()->next('Sunday')->setTime(11, 0),
                'location' => 'Main Sanctuary',
                'capacity' => 500,
                'requires_registration' => false,
                'registration_fee' => 0,
                'status' => 'upcoming',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Youth Conference 2025',
                'description' => 'An exciting 3-day conference for young people with workshops, worship, and fellowship.',
                'event_type' => 'conference',
                'start_date' => Carbon::now()->addDays(30)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(32)->setTime(17, 0),
                'location' => 'Conference Center',
                'capacity' => 200,
                'requires_registration' => true,
                'registration_fee' => 50.00,
                'status' => 'upcoming',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Community Outreach',
                'description' => 'Join us as we serve our community with food distribution and prayer.',
                'event_type' => 'outreach',
                'start_date' => Carbon::now()->addDays(7)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(7)->setTime(14, 0),
                'location' => 'City Park',
                'capacity' => 50,
                'requires_registration' => true,
                'registration_fee' => 0,
                'status' => 'upcoming',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Leadership Training',
                'description' => 'Equipping leaders with essential skills for effective ministry.',
                'event_type' => 'training',
                'start_date' => Carbon::now()->addDays(14)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(14)->setTime(16, 0),
                'location' => 'Training Room',
                'capacity' => 30,
                'requires_registration' => true,
                'registration_fee' => 25.00,
                'status' => 'upcoming',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Church Picnic',
                'description' => 'Family-friendly outdoor gathering with games, food, and fellowship.',
                'event_type' => 'social',
                'start_date' => Carbon::now()->addDays(21)->setTime(12, 0),
                'end_date' => Carbon::now()->addDays(21)->setTime(16, 0),
                'location' => 'Community Park',
                'capacity' => 150,
                'requires_registration' => true,
                'registration_fee' => 10.00,
                'status' => 'upcoming',
                'created_by' => $user->id,
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create($eventData);
            $this->command->info("Created event: {$event->title}");
        }

        $this->command->info('Events seeded successfully!');
    }
}
