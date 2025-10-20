<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EquipmentCategory;
use App\Models\Equipment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['name' => 'Sound System', 'description' => 'Audio equipment including microphones, speakers, mixers'],
            ['name' => 'Musical Instruments', 'description' => 'Guitars, drums, keyboards, and other instruments'],
            ['name' => 'Projectors & Screens', 'description' => 'Video projection equipment'],
            ['name' => 'Lighting', 'description' => 'Stage and ambient lighting equipment'],
            ['name' => 'Computers & IT', 'description' => 'Computers, laptops, tablets, and accessories'],
            ['name' => 'Furniture', 'description' => 'Chairs, tables, podiums'],
            ['name' => 'Vehicles', 'description' => 'Church vehicles and transportation'],
            ['name' => 'Kitchen Equipment', 'description' => 'Kitchen appliances and utensils'],
            ['name' => 'Cleaning Equipment', 'description' => 'Vacuum cleaners, cleaning supplies'],
            ['name' => 'Office Equipment', 'description' => 'Printers, copiers, office supplies'],
        ];

        foreach ($categories as $category) {
            EquipmentCategory::create($category);
        }

        // Get category IDs
        $soundCategory = EquipmentCategory::where('name', 'Sound System')->first();
        $instrumentCategory = EquipmentCategory::where('name', 'Musical Instruments')->first();
        $projectorCategory = EquipmentCategory::where('name', 'Projectors & Screens')->first();
        $lightingCategory = EquipmentCategory::where('name', 'Lighting')->first();
        $computerCategory = EquipmentCategory::where('name', 'Computers & IT')->first();

        // Create Sample Equipment
        $equipmentList = [
            [
                'name' => 'Main Sanctuary Speaker System',
                'category_id' => $soundCategory->id,
                'description' => 'Bose Professional sound system with dual speakers',
                'brand' => 'Bose',
                'model' => 'L1 Pro32',
                'serial_number' => 'BSE-2024-001',
                'purchase_date' => Carbon::now()->subYears(2),
                'purchase_price' => 5500.00,
                'vendor' => 'Audio Solutions Inc',
                'location' => 'Main Sanctuary',
                'condition' => 'excellent',
                'status' => 'available',
                'warranty_expiry' => Carbon::now()->addYear(),
                'maintenance_interval_days' => 180,
                'next_maintenance_date' => Carbon::now()->addDays(30),
            ],
            [
                'name' => 'Wireless Microphone Set',
                'category_id' => $soundCategory->id,
                'description' => 'Set of 4 wireless handheld microphones',
                'brand' => 'Shure',
                'model' => 'SM58',
                'serial_number' => 'SHR-2023-045',
                'purchase_date' => Carbon::now()->subYear(),
                'purchase_price' => 1200.00,
                'location' => 'Sound Booth',
                'condition' => 'good',
                'status' => 'available',
                'maintenance_interval_days' => 90,
                'next_maintenance_date' => Carbon::now()->addDays(15),
            ],
            [
                'name' => 'Electric Piano',
                'category_id' => $instrumentCategory->id,
                'description' => 'Digital piano for worship team',
                'brand' => 'Yamaha',
                'model' => 'P-125',
                'serial_number' => 'YMH-2023-012',
                'purchase_date' => Carbon::now()->subMonths(8),
                'purchase_price' => 800.00,
                'location' => 'Worship Stage',
                'condition' => 'excellent',
                'status' => 'in_use',
                'maintenance_interval_days' => 365,
                'next_maintenance_date' => Carbon::now()->addMonths(4),
            ],
            [
                'name' => 'Acoustic Guitar',
                'category_id' => $instrumentCategory->id,
                'description' => 'Steel string acoustic guitar',
                'brand' => 'Taylor',
                'model' => '214ce',
                'serial_number' => 'TLR-2022-089',
                'purchase_date' => Carbon::now()->subYears(2)->subMonths(3),
                'purchase_price' => 1100.00,
                'location' => 'Music Room',
                'condition' => 'good',
                'status' => 'available',
                'maintenance_interval_days' => 180,
                'next_maintenance_date' => Carbon::now()->addDays(45),
            ],
            [
                'name' => 'Main Projector',
                'category_id' => $projectorCategory->id,
                'description' => 'High-definition laser projector for main screen',
                'brand' => 'Epson',
                'model' => 'Pro L1070U',
                'serial_number' => 'EPS-2024-001',
                'purchase_date' => Carbon::now()->subMonths(6),
                'purchase_price' => 3200.00,
                'vendor' => 'Visual Tech Solutions',
                'location' => 'Main Sanctuary',
                'condition' => 'excellent',
                'status' => 'available',
                'warranty_expiry' => Carbon::now()->addYears(2)->addMonths(6),
                'maintenance_interval_days' => 365,
                'next_maintenance_date' => Carbon::now()->addMonths(6),
            ],
            [
                'name' => 'LED Stage Lights (Set of 6)',
                'category_id' => $lightingCategory->id,
                'description' => 'RGB LED stage lighting system',
                'brand' => 'Chauvet',
                'model' => 'SlimPAR Pro H USB',
                'serial_number' => 'CHV-2023-034',
                'purchase_date' => Carbon::now()->subYear()->subMonths(2),
                'purchase_price' => 1800.00,
                'location' => 'Worship Stage',
                'condition' => 'excellent',
                'status' => 'available',
                'maintenance_interval_days' => 180,
                'next_maintenance_date' => Carbon::now()->addDays(20),
            ],
            [
                'name' => 'Media Computer',
                'category_id' => $computerCategory->id,
                'description' => 'Desktop computer for running ProPresenter',
                'brand' => 'Dell',
                'model' => 'OptiPlex 7090',
                'serial_number' => 'DLL-2024-007',
                'purchase_date' => Carbon::now()->subMonths(3),
                'purchase_price' => 1500.00,
                'location' => 'Media Booth',
                'condition' => 'excellent',
                'status' => 'in_use',
                'warranty_expiry' => Carbon::now()->addYears(3),
                'maintenance_interval_days' => 365,
                'next_maintenance_date' => Carbon::now()->addMonths(9),
            ],
            [
                'name' => 'Livestream Camera',
                'category_id' => $computerCategory->id,
                'description' => 'PTZ camera for live streaming services',
                'brand' => 'Logitech',
                'model' => 'Rally Plus',
                'serial_number' => 'LGT-2023-089',
                'purchase_date' => Carbon::now()->subMonths(10),
                'purchase_price' => 2200.00,
                'location' => 'Back of Sanctuary',
                'condition' => 'good',
                'status' => 'available',
                'maintenance_interval_days' => 180,
                'next_maintenance_date' => Carbon::now()->addDays(60),
            ],
        ];

        foreach ($equipmentList as $equipment) {
            $equipment['equipment_code'] = 'EQP-' . strtoupper(Str::random(8));
            Equipment::create($equipment);
        }

        echo "✅ Equipment categories and sample data created successfully!\n";
    }
}
