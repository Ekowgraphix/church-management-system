<?php

echo "==========================================\n";
echo "CHECKING MEDIA FILES\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "1. Checking database records...\n";
$files = \App\Models\MediaFile::all();

if ($files->count() > 0) {
    echo "✅ Found {$files->count()} file(s) in database:\n\n";
    
    foreach ($files as $file) {
        echo "File ID: {$file->id}\n";
        echo "  Title: {$file->title}\n";
        echo "  Type: {$file->type}\n";
        echo "  File Name: {$file->file_name}\n";
        echo "  File Path: {$file->file_path}\n";
        echo "  File URL: {$file->file_url}\n";
        
        // Check if file exists
        $fullPath = storage_path('app/public/' . $file->file_path);
        if (file_exists($fullPath)) {
            $size = filesize($fullPath);
            echo "  ✅ File exists on disk: " . number_format($size / 1024, 2) . " KB\n";
        } else {
            echo "  ❌ File NOT found on disk!\n";
            echo "  Looking for: $fullPath\n";
        }
        
        echo "\n";
    }
} else {
    echo "⚠️  No files found in database\n";
}

echo "\n2. Checking storage symlink...\n";
$publicStorage = public_path('storage');
if (is_link($publicStorage)) {
    echo "✅ Storage symlink exists\n";
    echo "  Links to: " . readlink($publicStorage) . "\n";
} else if (file_exists($publicStorage)) {
    echo "⚠️  storage directory exists but is not a symlink\n";
} else {
    echo "❌ Storage symlink does NOT exist!\n";
    echo "  Run: php artisan storage:link\n";
}

echo "\n3. Checking storage directories...\n";
$dirs = [
    'storage/app/public/media/image',
    'storage/app/public/media/video',
    'storage/app/public/media/audio',
    'storage/app/public/media/document',
];

foreach ($dirs as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (file_exists($fullPath)) {
        $count = count(glob($fullPath . '/*'));
        echo "✅ $dir - $count file(s)\n";
    } else {
        echo "❌ $dir - NOT FOUND\n";
    }
}

echo "\n==========================================\n";
echo "✅ CHECK COMPLETE!\n";
echo "==========================================\n\n";
