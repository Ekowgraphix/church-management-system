<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// Create Volunteer user
$user = User::create([
    'name' => 'Joseph Mensah',
    'email' => 'joseph@volunteer.test',
    'password' => Hash::make('password123'),
    'phone' => '0123456789',
    'email_verified_at' => now(),
    'is_active' => true,
]);

// Assign Volunteer role
$volunteerRole = Role::where('name', 'Volunteer')->first();
if ($volunteerRole) {
    $user->assignRole($volunteerRole);
    echo "✅ Volunteer created successfully!\n";
    echo "Email: joseph@volunteer.test\n";
    echo "Password: password123\n";
} else {
    echo "❌ Error: Volunteer role not found. Please create roles first.\n";
}
