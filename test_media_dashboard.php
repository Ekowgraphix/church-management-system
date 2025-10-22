<?php

echo "==========================================\n";
echo "TESTING MEDIA DASHBOARD\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "1. Checking MediaFile model...\n";
try {
    $fileCount = \App\Models\MediaFile::count();
    echo "✅ MediaFile model works! Files: $fileCount\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n2. Checking MediaGallery model...\n";
try {
    $galleryCount = \App\Models\MediaGallery::count();
    echo "✅ MediaGallery model works! Galleries: $galleryCount\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n3. Checking MediaLivestream model...\n";
try {
    $streamCount = \App\Models\MediaLivestream::count();
    echo "✅ MediaLivestream model works! Streams: $streamCount\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n4. Checking MediaAnnouncement model...\n";
try {
    $announcementCount = \App\Models\MediaAnnouncement::count();
    echo "✅ MediaAnnouncement model works! Announcements: $announcementCount\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n5. Checking relationships...\n";
try {
    $file = \App\Models\MediaFile::with('uploader')->first();
    if ($file) {
        echo "✅ MediaFile->uploader relationship works!\n";
    } else {
        echo "⚠️  No media files yet to test relationship\n";
    }
} catch (\Exception $e) {
    echo "❌ Relationship error: " . $e->getMessage() . "\n";
}

echo "\n6. Testing dashboard stats query...\n";
try {
    $stats = [
        'videos' => \App\Models\MediaFile::where('type', 'video')->whereMonth('created_at', now()->month)->count(),
        'photos' => \App\Models\MediaFile::where('type', 'image')->whereMonth('created_at', now()->month)->count(),
        'total_views' => \App\Models\MediaFile::sum('views_count'),
        'storage' => \App\Models\MediaFile::sum('file_size'),
    ];
    echo "✅ Dashboard stats query works!\n";
    echo "   Videos: {$stats['videos']}\n";
    echo "   Photos: {$stats['photos']}\n";
    echo "   Views: {$stats['total_views']}\n";
    echo "   Storage: " . number_format($stats['storage'] / 1048576, 2) . " MB\n";
} catch (\Exception $e) {
    echo "❌ Stats query error: " . $e->getMessage() . "\n";
}

echo "\n==========================================\n";
echo "✅ TEST COMPLETE!\n";
echo "==========================================\n\n";

echo "Now try accessing:\n";
echo "http://127.0.0.1:8000/media/dashboard\n\n";
