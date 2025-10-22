<?php

echo "==========================================\n";
echo "TESTING MEDIA PORTAL FUNCTIONALITY\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "1. Checking database tables...\n";
$tables = [
    'media_files',
    'media_galleries',
    'media_livestreams',
    'media_announcements',
    'media_event_assignments'
];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "   ✅ $table - $count records\n";
    } catch (Exception $e) {
        echo "   ❌ $table - Error: " . $e->getMessage() . "\n";
    }
}

echo "\n2. Checking models...\n";
$models = [
    'MediaFile' => \App\Models\MediaFile::class,
    'MediaGallery' => \App\Models\MediaGallery::class,
    'MediaLivestream' => \App\Models\MediaLivestream::class,
    'MediaAnnouncement' => \App\Models\MediaAnnouncement::class,
    'MediaEventAssignment' => \App\Models\MediaEventAssignment::class,
];

foreach ($models as $name => $class) {
    try {
        $instance = new $class();
        echo "   ✅ $name model - OK\n";
    } catch (Exception $e) {
        echo "   ❌ $name model - Error: " . $e->getMessage() . "\n";
    }
}

echo "\n3. Checking routes...\n";
$routes = [
    'media.dashboard',
    'media.library',
    'media.library.upload',
    'media.gallery',
    'media.livestream',
    'media.announcements',
    'media.team',
    'media.analytics',
    'media.settings'
];

foreach ($routes as $route) {
    try {
        $url = route($route);
        echo "   ✅ $route - $url\n";
    } catch (Exception $e) {
        echo "   ❌ $route - Not found\n";
    }
}

echo "\n4. Testing sample data creation...\n";
try {
    // Check if we can create a test announcement
    $user = \App\Models\User::role('Media Team')->first();
    if ($user) {
        echo "   ✅ Found media team user: {$user->name}\n";
        echo "   ID: {$user->id}\n";
    } else {
        echo "   ⚠️  No media team users found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n==========================================\n";
echo "✅ TEST COMPLETE!\n";
echo "==========================================\n\n";

echo "Issues Found:\n";
echo "- Check that all database migrations ran successfully\n";
echo "- Ensure media team role and user exist\n";
echo "- Verify routes are registered\n\n";

echo "To fix issues:\n";
echo "1. php artisan migrate --force\n";
echo "2. php artisan db:seed (if you have seeders)\n";
echo "3. Check create_media_team_role_and_user.php ran\n\n";
