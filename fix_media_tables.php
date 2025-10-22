<?php

echo "==========================================\n";
echo "FIXING MEDIA TABLES\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "1. Dropping old media tables if they exist...\n";

try {
    Schema::dropIfExists('gallery_media');
    echo "✅ Dropped gallery_media\n";
} catch (\Exception $e) {
    echo "⚠️  gallery_media didn't exist\n";
}

try {
    Schema::dropIfExists('media_galleries');
    echo "✅ Dropped media_galleries\n";
} catch (\Exception $e) {
    echo "⚠️  media_galleries didn't exist\n";
}

try {
    Schema::dropIfExists('media_livestreams');
    echo "✅ Dropped media_livestreams\n";
} catch (\Exception $e) {
    echo "⚠️  media_livestreams didn't exist\n";
}

try {
    Schema::dropIfExists('media_announcements');
    echo "✅ Dropped media_announcements\n";
} catch (\Exception $e) {
    echo "⚠️  media_announcements didn't exist\n";
}

try {
    Schema::dropIfExists('media_event_assignments');
    echo "✅ Dropped media_event_assignments\n";
} catch (\Exception $e) {
    echo "⚠️  media_event_assignments didn't exist\n";
}

try {
    Schema::dropIfExists('media_files');
    echo "✅ Dropped old media_files table\n";
} catch (\Exception $e) {
    echo "⚠️  media_files didn't exist\n";
}

echo "\n2. Running migrations...\n";
\Artisan::call('migrate', ['--force' => true]);
echo \Artisan::output();

echo "\n==========================================\n";
echo "✅ TABLES FIXED!\n";
echo "==========================================\n\n";

echo "Now try:\n";
echo "php test_media_dashboard.php\n\n";
