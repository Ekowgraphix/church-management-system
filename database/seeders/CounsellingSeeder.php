<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Counsellor;
use App\Models\CounsellingCategory;
use App\Models\CounsellingSession;
use Carbon\Carbon;

class CounsellingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Counsellors
        $counsellors = [
            [
                'name' => 'Pastor John Smith',
                'email' => 'pastor.john@church.org',
                'phone' => '+1234567890',
                'specialization' => 'Marriage',
                'status' => 'Active',
                'bio' => 'Senior Pastor with 15+ years experience in marriage and family counselling.',
                'total_sessions' => 145,
                'rating' => 5,
            ],
            [
                'name' => 'Elder Sarah Johnson',
                'email' => 'sarah.j@church.org',
                'phone' => '+1234567891',
                'specialization' => 'Family',
                'status' => 'Active',
                'bio' => 'Licensed family therapist specializing in parent-child relationships.',
                'total_sessions' => 98,
                'rating' => 5,
            ],
            [
                'name' => 'Deacon Mark Williams',
                'email' => 'mark.w@church.org',
                'phone' => '+1234567892',
                'specialization' => 'Youth',
                'status' => 'Active',
                'bio' => 'Youth pastor with expertise in adolescent counselling.',
                'total_sessions' => 67,
                'rating' => 4,
            ],
            [
                'name' => 'Sister Grace Davis',
                'email' => 'grace.d@church.org',
                'phone' => '+1234567893',
                'specialization' => 'Grief',
                'status' => 'Active',
                'bio' => 'Grief counsellor and bereavement support specialist.',
                'total_sessions' => 52,
                'rating' => 5,
            ],
            [
                'name' => 'Brother Michael Brown',
                'email' => 'michael.b@church.org',
                'phone' => '+1234567894',
                'specialization' => 'Career',
                'status' => 'Active',
                'bio' => 'Business mentor and career transition counsellor.',
                'total_sessions' => 43,
                'rating' => 4,
            ],
        ];

        foreach ($counsellors as $counsellor) {
            Counsellor::create($counsellor);
        }

        // Create Categories
        $categories = [
            [
                'name' => 'Marriage',
                'icon' => 'ring',
                'color' => 'pink',
                'description' => 'Marriage counselling, pre-marital preparation, and relationship enrichment',
                'requires_specialist' => true,
                'session_count' => 34,
            ],
            [
                'name' => 'Family',
                'icon' => 'home',
                'color' => 'blue',
                'description' => 'Family dynamics, parenting support, and family conflict resolution',
                'requires_specialist' => false,
                'session_count' => 28,
            ],
            [
                'name' => 'Personal',
                'icon' => 'user',
                'color' => 'purple',
                'description' => 'Personal growth, identity issues, and life transitions',
                'requires_specialist' => false,
                'session_count' => 45,
            ],
            [
                'name' => 'Youth',
                'icon' => 'users',
                'color' => 'cyan',
                'description' => 'Adolescent issues, peer pressure, and identity formation',
                'requires_specialist' => true,
                'session_count' => 22,
            ],
            [
                'name' => 'Career',
                'icon' => 'briefcase',
                'color' => 'orange',
                'description' => 'Career guidance, job transitions, and work-life balance',
                'requires_specialist' => false,
                'session_count' => 18,
            ],
            [
                'name' => 'Grief',
                'icon' => 'heart-broken',
                'color' => 'red',
                'description' => 'Loss and bereavement support, grief processing',
                'requires_specialist' => true,
                'session_count' => 12,
            ],
            [
                'name' => 'Spiritual',
                'icon' => 'praying-hands',
                'color' => 'green',
                'description' => 'Spiritual direction, faith questions, and biblical guidance',
                'requires_specialist' => false,
                'session_count' => 56,
            ],
        ];

        foreach ($categories as $category) {
            CounsellingCategory::create($category);
        }

        // Create Sample Sessions
        $this->createSampleSessions();

        $this->command->info('✅ Counselling system seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   - ' . count($counsellors) . ' Counsellors');
        $this->command->info('   - ' . count($categories) . ' Categories');
        $this->command->info('   - 10 Sample Sessions');
    }

    /**
     * Create sample counselling sessions
     */
    private function createSampleSessions(): void
    {
        $sessions = [
            [
                'counsellor_id' => 1,
                'counsellor_name' => 'Pastor John Smith',
                'category_id' => 1, // Marriage
                'member_name' => 'John & Mary Doe',
                'member_contact' => '+1234567800',
                'topic' => 'Communication Issues',
                'session_date' => Carbon::now()->addDays(3),
                'session_time' => Carbon::now()->addDays(3)->setTime(14, 0),
                'session_type' => 'In-Person',
                'duration' => 60,
                'status' => 'pending',
                'is_confidential' => true,
                'access_level' => 'private',
            ],
            [
                'counsellor_id' => 2,
                'counsellor_name' => 'Elder Sarah Johnson',
                'category_id' => 2, // Family
                'member_name' => 'Sarah Thompson',
                'member_contact' => '+1234567801',
                'topic' => 'Parenting Challenges',
                'session_date' => Carbon::now()->subDays(5),
                'session_time' => Carbon::now()->subDays(5)->setTime(10, 0),
                'session_type' => 'In-Person',
                'duration' => 45,
                'notes' => 'Discussed effective discipline strategies and positive reinforcement.',
                'outcome' => 'Parent gained new tools and committed to implementing strategies.',
                'rating' => 5,
                'status' => 'completed',
                'follow_up_date' => Carbon::now()->addDays(14),
                'requires_followup' => true,
                'is_confidential' => false,
                'access_level' => 'confidential',
            ],
            [
                'counsellor_id' => 3,
                'counsellor_name' => 'Deacon Mark Williams',
                'category_id' => 4, // Youth
                'member_name' => 'Teen Group Session',
                'member_contact' => '+1234567802',
                'topic' => 'Peer Pressure & Identity',
                'session_date' => Carbon::now()->addDays(1),
                'session_time' => Carbon::now()->addDays(1)->setTime(16, 0),
                'session_type' => 'Group',
                'duration' => 90,
                'status' => 'pending',
                'is_confidential' => false,
                'access_level' => 'confidential',
            ],
            [
                'counsellor_id' => 4,
                'counsellor_name' => 'Sister Grace Davis',
                'category_id' => 6, // Grief
                'member_name' => 'Michael Anderson',
                'member_contact' => '+1234567803',
                'topic' => 'Loss of Spouse',
                'session_date' => Carbon::now()->subDays(2),
                'session_time' => Carbon::now()->subDays(2)->setTime(11, 0),
                'session_type' => 'In-Person',
                'duration' => 60,
                'notes' => 'Initial grief counselling session. Client expressing healthy grief process.',
                'outcome' => 'Good progress, connected with support group.',
                'rating' => 5,
                'status' => 'completed',
                'follow_up_date' => Carbon::now()->addDays(7),
                'requires_followup' => true,
                'is_confidential' => true,
                'access_level' => 'private',
            ],
            [
                'counsellor_id' => 5,
                'counsellor_name' => 'Brother Michael Brown',
                'category_id' => 5, // Career
                'member_name' => 'David Lee',
                'member_contact' => '+1234567804',
                'topic' => 'Career Transition',
                'session_date' => Carbon::now()->addDays(7),
                'session_time' => Carbon::now()->addDays(7)->setTime(15, 0),
                'session_type' => 'Video Call',
                'duration' => 45,
                'status' => 'pending',
                'is_confidential' => false,
                'access_level' => 'confidential',
            ],
            [
                'counsellor_id' => 1,
                'counsellor_name' => 'Pastor John Smith',
                'category_id' => 7, // Spiritual
                'member_name' => 'Emily White',
                'member_contact' => '+1234567805',
                'topic' => 'Faith Questions',
                'session_date' => Carbon::now()->subDays(10),
                'session_time' => Carbon::now()->subDays(10)->setTime(13, 0),
                'session_type' => 'In-Person',
                'duration' => 50,
                'notes' => 'Discussed doubts and questions about faith. Provided biblical perspectives.',
                'outcome' => 'Client felt reassured and spiritually encouraged.',
                'rating' => 4,
                'status' => 'completed',
                'is_confidential' => false,
                'access_level' => 'confidential',
            ],
            [
                'counsellor_id' => 2,
                'counsellor_name' => 'Elder Sarah Johnson',
                'category_id' => 3, // Personal
                'member_name' => 'Robert Martinez',
                'member_contact' => '+1234567806',
                'topic' => 'Life Direction',
                'session_date' => Carbon::now()->addDays(5),
                'session_time' => Carbon::now()->addDays(5)->setTime(9, 30),
                'session_type' => 'In-Person',
                'duration' => 60,
                'status' => 'pending',
                'is_confidential' => false,
                'access_level' => 'confidential',
            ],
            [
                'counsellor_id' => 1,
                'counsellor_name' => 'Pastor John Smith',
                'category_id' => 1, // Marriage
                'member_name' => 'Tom & Lisa Garcia',
                'member_contact' => '+1234567807',
                'topic' => 'Pre-Marital Counselling',
                'session_date' => Carbon::now()->subDays(15),
                'session_time' => Carbon::now()->subDays(15)->setTime(17, 0),
                'session_type' => 'In-Person',
                'duration' => 75,
                'notes' => 'Covered communication, finances, and conflict resolution in pre-marital session.',
                'outcome' => 'Couple showed good compatibility and readiness for marriage.',
                'rating' => 5,
                'status' => 'completed',
                'follow_up_date' => Carbon::now()->addDays(30),
                'requires_followup' => true,
                'is_confidential' => true,
                'access_level' => 'private',
            ],
            [
                'counsellor_id' => 3,
                'counsellor_name' => 'Deacon Mark Williams',
                'category_id' => 4, // Youth
                'member_name' => 'Jessica Brown',
                'member_contact' => '+1234567808',
                'topic' => 'Academic Pressure',
                'session_date' => Carbon::now()->addDays(2),
                'session_time' => Carbon::now()->addDays(2)->setTime(16, 30),
                'session_type' => 'In-Person',
                'duration' => 45,
                'status' => 'pending',
                'is_confidential' => true,
                'access_level' => 'private',
            ],
            [
                'counsellor_id' => 4,
                'counsellor_name' => 'Sister Grace Davis',
                'category_id' => 2, // Family
                'member_name' => 'The Johnson Family',
                'member_contact' => '+1234567809',
                'topic' => 'Family Conflict Resolution',
                'session_date' => Carbon::now()->subDays(3),
                'session_time' => Carbon::now()->subDays(3)->setTime(14, 30),
                'session_type' => 'In-Person',
                'duration' => 90,
                'notes' => 'Family session to address ongoing conflicts. Made progress on communication.',
                'outcome' => 'Family agreed to implement conflict resolution strategies.',
                'rating' => 4,
                'status' => 'completed',
                'follow_up_date' => Carbon::now()->addDays(21),
                'requires_followup' => true,
                'is_confidential' => false,
                'access_level' => 'confidential',
            ],
        ];

        foreach ($sessions as $session) {
            CounsellingSession::create($session);
        }
    }
}
