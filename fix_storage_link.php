<?php

echo "==========================================\n";
echo "FIXING STORAGE SYMLINK\n";
echo "==========================================\n\n";

$publicStorage = __DIR__ . '/public/storage';

echo "1. Checking current public/storage...\n";
if (file_exists($publicStorage)) {
    if (is_link($publicStorage)) {
        echo "   Already a symlink\n";
    } else {
        echo "   ❌ Exists but NOT a symlink (probably empty directory)\n";
        echo "   Removing it...\n";
        
        // Remove directory
        if (is_dir($publicStorage)) {
            rmdir($publicStorage);
            echo "   ✅ Removed\n";
        }
    }
} else {
    echo "   Does not exist\n";
}

echo "\n2. Creating proper symlink...\n";
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

// On Windows, we need to use junction instead of symlink
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    echo "   Detected Windows OS\n";
    echo "   Using junction point...\n";
    
    $cmd = 'mklink /J "' . $link . '" "' . $target . '"';
    exec($cmd, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ Junction created successfully!\n";
    } else {
        echo "   ❌ Failed to create junction\n";
        echo "   Try running as Administrator\n";
        echo "   Or run manually: php artisan storage:link\n";
    }
} else {
    if (symlink($target, $link)) {
        echo "   ✅ Symlink created successfully!\n";
    } else {
        echo "   ❌ Failed to create symlink\n";
    }
}

echo "\n3. Verifying...\n";
if (is_link($link) || (is_dir($link) && file_exists($link))) {
    echo "   ✅ public/storage is now accessible\n";
    
    // Test with uploaded file
    $testFile = $link . '/media/image/c2bxa0VzzduG8zgGxIVBlZJe3bZhlieGW71qVxO7.jpg';
    if (file_exists($testFile)) {
        echo "   ✅ Uploaded image is now accessible!\n";
        echo "   URL: http://127.0.0.1:8000/storage/media/image/c2bxa0VzzduG8zgGxIVBlZJe3bZhlieGW71qVxO7.jpg\n";
    }
} else {
    echo "   ❌ Still not accessible\n";
}

echo "\n==========================================\n";
echo "✅ DONE!\n";
echo "==========================================\n\n";
echo "Refresh your browser (Ctrl+F5) to see the images!\n\n";
