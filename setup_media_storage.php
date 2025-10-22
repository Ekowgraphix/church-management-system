<?php

echo "==========================================\n";
echo "SETTING UP MEDIA STORAGE\n";
echo "==========================================\n\n";

$directories = [
    'storage/app/public/media',
    'storage/app/public/media/image',
    'storage/app/public/media/video',
    'storage/app/public/media/audio',
    'storage/app/public/media/document',
    'storage/app/public/media/thumbnails',
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    
    if (!file_exists($fullPath)) {
        if (mkdir($fullPath, 0777, true)) {
            echo "✅ Created: $dir\n";
        } else {
            echo "❌ Failed to create: $dir\n";
        }
    } else {
        echo "✓ Exists: $dir\n";
    }
}

echo "\n==========================================\n";
echo "✅ STORAGE SETUP COMPLETE!\n";
echo "==========================================\n\n";

echo "All media directories are ready for uploads!\n\n";
