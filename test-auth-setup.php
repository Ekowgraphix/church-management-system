<?php

/**
 * Auth Module Setup Test Script
 * Run this with: php test-auth-setup.php
 */

echo "=================================================\n";
echo "   Church Management System - Auth Module Test  \n";
echo "=================================================\n\n";

// Check if running in Laravel context
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "❌ Error: vendor/autoload.php not found. Run 'composer install' first.\n";
    exit(1);
}

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Auth Module Setup...\n\n";

// Test 1: Check if migrations are run
echo "1. Checking database tables...\n";
try {
    $tables = DB::select("SHOW TABLES");
    $tableNames = array_map(function($table) {
        return array_values((array)$table)[0];
    }, $tables);
    
    $requiredTables = ['users', 'members', 'email_verifications', 'roles', 'model_has_roles'];
    $missingTables = [];
    
    foreach ($requiredTables as $table) {
        if (in_array($table, $tableNames)) {
            echo "   ✅ Table '$table' exists\n";
        } else {
            echo "   ❌ Table '$table' is missing\n";
            $missingTables[] = $table;
        }
    }
    
    if (count($missingTables) > 0) {
        echo "\n   ⚠️  Run: php artisan migrate\n";
    }
} catch (Exception $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check if roles are seeded
echo "2. Checking roles...\n";
try {
    $roles = \Spatie\Permission\Models\Role::all();
    $requiredRoles = ['Admin', 'Pastor', 'Ministry Leader', 'Volunteer', 'Organization', 'Church Member'];
    
    if ($roles->count() > 0) {
        foreach ($requiredRoles as $roleName) {
            $exists = $roles->contains('name', $roleName);
            if ($exists) {
                echo "   ✅ Role '$roleName' exists\n";
            } else {
                echo "   ❌ Role '$roleName' is missing\n";
            }
        }
        
        if ($roles->count() < 6) {
            echo "\n   ⚠️  Run: php artisan db:seed --class=RolesSeeder\n";
        }
    } else {
        echo "   ❌ No roles found\n";
        echo "   ⚠️  Run: php artisan db:seed --class=RolesSeeder\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error checking roles: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Check if views exist
echo "3. Checking view files...\n";
$views = [
    'resources/views/auth/login.blade.php' => 'Login page',
    'resources/views/auth/signup.blade.php' => 'Signup page',
    'resources/views/emails/verify.blade.php' => 'Verification email',
    'resources/views/portal/index.blade.php' => 'Member portal',
];

foreach ($views as $path => $description) {
    if (file_exists(__DIR__ . '/' . $path)) {
        echo "   ✅ $description ($path)\n";
    } else {
        echo "   ❌ $description ($path) - MISSING\n";
    }
}

echo "\n";

// Test 4: Check email configuration
echo "4. Checking email configuration...\n";
$mailDriver = env('MAIL_MAILER', 'not set');
$mailHost = env('MAIL_HOST', 'not set');

if ($mailDriver !== 'not set' && $mailHost !== 'not set') {
    echo "   ✅ Mail driver: $mailDriver\n";
    echo "   ✅ Mail host: $mailHost\n";
} else {
    echo "   ⚠️  Email not configured in .env\n";
    echo "   Add MAIL_MAILER, MAIL_HOST, etc. to .env file\n";
}

echo "\n";

// Test 5: Check routes
echo "5. Checking routes...\n";
$routes = [
    'login' => 'Login page',
    'signup' => 'Signup page',
    'verify.email' => 'Email verification',
    'portal.index' => 'Member portal',
];

foreach ($routes as $routeName => $description) {
    try {
        $url = route($routeName, $routeName === 'verify.email' ? ['token' => 'test'] : []);
        echo "   ✅ $description: $url\n";
    } catch (Exception $e) {
        echo "   ❌ $description route not found\n";
    }
}

echo "\n";

// Test 6: Create test user (optional)
echo "6. Test user creation?\n";
echo "   Would you like to create a test Church Member? (yes/no): ";

if (php_sapi_name() === 'cli') {
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    $response = trim(strtolower($line));
    fclose($handle);
    
    if ($response === 'yes' || $response === 'y') {
        echo "\n   Enter test user details:\n";
        
        echo "   Full Name: ";
        $handle = fopen("php://stdin", "r");
        $fullname = trim(fgets($handle));
        fclose($handle);
        
        echo "   Email: ";
        $handle = fopen("php://stdin", "r");
        $email = trim(fgets($handle));
        fclose($handle);
        
        echo "   Password: ";
        $handle = fopen("php://stdin", "r");
        $password = trim(fgets($handle));
        fclose($handle);
        
        try {
            // Create user
            $user = \App\Models\User::create([
                'name' => $fullname,
                'email' => $email,
                'password' => \Hash::make($password),
                'email_verified_at' => now(), // Auto-verify for testing
                'is_active' => true,
            ]);
            
            // Assign role
            $role = \Spatie\Permission\Models\Role::where('name', 'Church Member')->first();
            $user->assignRole($role);
            
            // Create member profile
            $nameParts = explode(' ', $fullname);
            $firstName = $nameParts[0] ?? '';
            $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
            
            \App\Models\Member::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => '000-000-0000',
                'membership_status' => 'active',
                'membership_date' => now(),
            ]);
            
            echo "\n   ✅ Test user created successfully!\n";
            echo "   Email: $email\n";
            echo "   Password: (hidden)\n";
            echo "   Role: Church Member\n";
            echo "   Status: Verified & Active\n";
            echo "\n   You can now login at: " . url('/login') . "\n";
            
        } catch (Exception $e) {
            echo "\n   ❌ Error creating test user: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n=================================================\n";
echo "   Setup Test Complete!\n";
echo "=================================================\n\n";

echo "📋 Summary:\n";
echo "   - Visit: " . url('/login') . " to login\n";
echo "   - Visit: " . url('/signup') . " to register\n";
echo "   - Check: AUTH_MODULE_IMPLEMENTATION_GUIDE.md for full documentation\n\n";

echo "🚀 Next Steps:\n";
echo "   1. Fix any ❌ issues shown above\n";
echo "   2. Configure email in .env (for production)\n";
echo "   3. Test the signup → verify → login flow\n";
echo "   4. Access member portal after login\n\n";

echo "Happy testing! 🎉\n\n";
