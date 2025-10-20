<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmallGroup;
use App\Models\Member;

class SmallGroupSeeder extends Seeder
{
    public function run()
    {
        // Get first member as leader
        $leader = Member::first();

        if (!$leader) {
            $this->command->warn('No members found. Please create members first.');
            return;
        }

        // Create sample small groups
        $groups = [
            [
                'name' => 'Sunday Bible Study',
                'description' => 'Weekly Bible study group focusing on New Testament teachings',
                'category' => 'bible_study',
                'leader_id' => $leader->id,
                'meeting_day' => 'sunday',
                'meeting_time' => '14:00',
                'location' => 'Church Hall Room 1',
                'max_members' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Wednesday Prayer Group',
                'description' => 'Midweek prayer and intercession gathering',
                'category' => 'prayer',
                'leader_id' => $leader->id,
                'meeting_day' => 'wednesday',
                'meeting_time' => '18:30',
                'location' => 'Prayer Room',
                'max_members' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Youth Fellowship',
                'description' => 'Dynamic group for young adults aged 18-30',
                'category' => 'youth',
                'leader_id' => $leader->id,
                'meeting_day' => 'friday',
                'meeting_time' => '19:00',
                'location' => 'Youth Center',
                'max_members' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Men\'s Ministry',
                'description' => 'Fellowship and discipleship for men',
                'category' => 'men',
                'leader_id' => $leader->id,
                'meeting_day' => 'saturday',
                'meeting_time' => '08:00',
                'location' => 'Conference Room',
                'max_members' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Women of Faith',
                'description' => 'Women\'s fellowship and Bible study',
                'category' => 'women',
                'leader_id' => $leader->id,
                'meeting_day' => 'tuesday',
                'meeting_time' => '10:00',
                'location' => 'Fellowship Hall',
                'max_members' => 25,
                'is_active' => true,
            ],
        ];

        foreach ($groups as $groupData) {
            $group = SmallGroup::create($groupData);

            // Add leader as member
            $group->members()->attach($leader->id, [
                'joined_date' => now(),
                'role' => 'leader',
                'is_active' => true,
            ]);

            $this->command->info("Created small group: {$group->name}");
        }

        $this->command->info('Small groups seeded successfully!');
    }
}
