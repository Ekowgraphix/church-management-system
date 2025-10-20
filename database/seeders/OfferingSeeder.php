<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offering;
use Carbon\Carbon;

class OfferingSeeder extends Seeder
{
    public function run()
    {
        $categories = ['Sunday Offering', 'Thanksgiving', 'Harvest', 'Special', 'Missions', 'Building Fund'];
        $services = ['Sunday Morning Service', 'Sunday Evening Service', 'Midweek Service', 'Prayer Meeting', 'Youth Service'];
        $paymentMethods = ['Cash', 'Mobile Money', 'Bank Transfer', 'Cheque', 'Card'];
        $collectors = ['John Mensah', 'Mary Adu', 'Peter Asante', 'Grace Owusu', 'Samuel Boateng'];

        // Create offerings for the last 12 months
        for ($month = 11; $month >= 0; $month--) {
            $servicesThisMonth = rand(8, 16); // 8-16 services per month

            for ($i = 0; $i < $servicesThisMonth; $i++) {
                $date = Carbon::now()->subMonths($month)->addDays(rand(0, 27));

                Offering::create([
                    'date' => $date,
                    'service_name' => $services[array_rand($services)],
                    'category' => $categories[array_rand($categories)],
                    'amount' => rand(50000, 1500000) / 100, // GHS 500 - 15,000
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'collected_by' => $collectors[array_rand($collectors)],
                    'remarks' => rand(0, 1) ? null : 'Regular collection',
                ]);
            }
        }

        // Add some special recent offerings
        $specialOfferings = [
            [
                'date' => Carbon::today(),
                'service_name' => 'Sunday Morning Service',
                'category' => 'Sunday Offering',
                'amount' => 8500.00,
                'payment_method' => 'Cash',
                'collected_by' => 'John Mensah',
                'remarks' => "Today's collection - very good turnout",
            ],
            [
                'date' => Carbon::today()->subDays(7),
                'service_name' => 'Thanksgiving Service',
                'category' => 'Thanksgiving',
                'amount' => 15200.00,
                'payment_method' => 'Mobile Money',
                'collected_by' => 'Mary Adu',
                'remarks' => 'Special thanksgiving offering for new church building',
            ],
            [
                'date' => Carbon::today()->subDays(14),
                'service_name' => 'Harvest Festival',
                'category' => 'Harvest',
                'amount' => 25000.00,
                'payment_method' => 'Bank Transfer',
                'collected_by' => 'Peter Asante',
                'remarks' => 'Annual harvest festival - record breaking!',
            ],
        ];

        foreach ($specialOfferings as $offering) {
            Offering::create($offering);
        }

        $this->command->info('Created ' . Offering::count() . ' offering records');
    }
}
