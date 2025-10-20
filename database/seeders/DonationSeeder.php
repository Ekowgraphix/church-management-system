<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\Member;
use App\Models\DonationCategory;
use App\Models\User;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // Get first member and user
        $member = Member::first();
        $user = User::first();
        $category = DonationCategory::first();

        if (!$member || !$user) {
            $this->command->info('No members or users found. Skipping donation seeding.');
            return;
        }

        if (!$category) {
            // Create a default category
            $category = DonationCategory::create([
                'name' => 'Tithe',
                'description' => 'Regular tithe offerings',
                'is_active' => true
            ]);
        }

        // Create donation records for the past 3 months
        for ($i = 0; $i < 3; $i++) {
            $date = Carbon::now()->subMonths($i);
            
            Donation::create([
                'transaction_id' => 'TXN-' . uniqid(),
                'member_id' => $member->id,
                'donation_category_id' => $category->id,
                'amount' => rand(50, 500),
                'donation_date' => $date,
                'payment_method' => 'cash',
                'status' => 'completed',
                'receipt_number' => 'RCP-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'recorded_by' => $user->id,
                'notes' => 'Sample donation record'
            ]);
        }

        $this->command->info('Donation records seeded successfully!');
    }
}
