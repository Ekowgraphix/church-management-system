<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "==========================================\n";
echo "TEAM MANAGEMENT BACKEND TEST\n";
echo "==========================================\n\n";

// Test 1: Check if Media Team role exists
echo "1. Checking Media Team role...\n";
try {
    $role = Spatie\Permission\Models\Role::where('name', 'Media Team')->first();
    if ($role) {
        echo "   ✅ Media Team role EXISTS\n";
        echo "   ID: {$role->id}\n";
    } else {
        echo "   ❌ Media Team role NOT FOUND\n";
        echo "   Run: php create_media_team_role_and_user.php\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 2: Check current media team members
echo "\n2. Current Media Team members...\n";
try {
    $members = \App\Models\User::role('Media Team')->get();
    echo "   Total: {$members->count()}\n";
    if ($members->count() > 0) {
        foreach ($members as $member) {
            $status = $member->is_active ? 'Active' : 'Inactive';
            echo "   - {$member->name} ({$member->email}) - {$status}\n";
        }
    } else {
        echo "   No members yet\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 3: Test user creation (dry run)
echo "\n3. Testing user creation logic...\n";
try {
    $testEmail = 'test' . time() . '@example.com';
    echo "   Creating test user: {$testEmail}\n";
    
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'email' => $testEmail,
        'password' => Hash::make('password'),
        'phone' => '123-456-7890',
        'is_active' => true,
    ]);
    
    // Assign role
    $user->assignRole('Media Team');
    
    echo "   ✅ User created successfully!\n";
    echo "   ID: {$user->id}\n";
    echo "   Name: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
    
    // Check role assignment
    $hasRole = $user->hasRole('Media Team');
    if ($hasRole) {
        echo "   ✅ Role assigned successfully!\n";
    } else {
        echo "   ❌ Role assignment FAILED!\n";
    }
    
    // Clean up test user
    echo "\n   Cleaning up test user...\n";
    $user->delete();
    echo "   ✅ Test user deleted\n";
    
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 4: Check routes
echo "\n4. Checking routes...\n";
try {
    $routes = [
        'media.team' => 'GET /media/team',
        'media.team.add' => 'POST /media/team/add',
        'media.team.update' => 'PUT /media/team/{id}',
        'media.team.delete' => 'DELETE /media/team/{id}',
    ];
    
    foreach ($routes as $name => $description) {
        try {
            $url = route($name, ['id' => 1], false);
            echo "   ✅ {$name} - {$url}\n";
        } catch (Exception $e) {
            echo "   ❌ {$name} - NOT FOUND\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 5: Check controller methods exist
echo "\n5. Checking controller methods...\n";
try {
    $controller = new \App\Http\Controllers\MediaPortalController();
    
    $methods = ['team', 'addTeamMember', 'updateTeamMember', 'deleteTeamMember'];
    foreach ($methods as $method) {
        if (method_exists($controller, $method)) {
            echo "   ✅ {$method}() method exists\n";
        } else {
            echo "   ❌ {$method}() method NOT FOUND\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n==========================================\n";
echo "✅ TEST COMPLETE!\n";
echo "==========================================\n\n";

echo "Summary:\n";
echo "- If all tests pass ✅, backend is working!\n";
echo "- If you see ❌, there's an issue to fix\n";
echo "- Test by going to: http://127.0.0.1:8000/media/team\n\n";
