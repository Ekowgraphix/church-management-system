<?php

echo "==========================================\n";
echo "SYNCING STORAGE FILES NOW\n";
echo "==========================================\n\n";

function copyDirectory($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst, 0777, true);
    
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDirectory($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    
    closedir($dir);
}

$source = __DIR__ . '/storage/app/public';
$destination = __DIR__ . '/public/storage';

echo "1. Creating public/storage directory...\n";
if (!file_exists($destination)) {
    mkdir($destination, 0777, true);
    echo "   ✅ Created\n";
} else {
    echo "   Already exists\n";
}

echo "\n2. Copying all files from storage/app/public to public/storage...\n";
try {
    copyDirectory($source, $destination);
    echo "   ✅ Files copied successfully!\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n3. Verifying image is accessible...\n";
$testFile = $destination . '/media/image/c2bxa0VzzduG8zgGxIVBlZJe3bZhlieGW71qVxO7.jpg';
if (file_exists($testFile)) {
    $size = filesize($testFile);
    echo "   ✅ Image file is now accessible!\n";
    echo "   Size: " . number_format($size / 1024, 2) . " KB\n";
    echo "   \n";
    echo "   URL: http://127.0.0.1:8000/storage/media/image/c2bxa0VzzduG8zgGxIVBlZJe3bZhlieGW71qVxO7.jpg\n";
} else {
    echo "   ❌ File not found after copy\n";
}

echo "\n==========================================\n";
echo "✅ SYNC COMPLETE!\n";
echo "==========================================\n\n";
echo "Now refresh your browser (Ctrl+F5)\n";
echo "Your image should be visible!\n\n";

echo "NOTE: After each new upload, run this script again\n";
echo "or better yet, run create_storage_link.bat as Administrator\n";
echo "for a permanent solution.\n\n";
