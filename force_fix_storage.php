<?php

echo "==========================================\n";
echo "FORCE FIX STORAGE SYMLINK\n";
echo "==========================================\n\n";

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

$publicStorage = __DIR__ . '/public/storage';

echo "1. Removing existing public/storage completely...\n";
if (file_exists($publicStorage)) {
    if (deleteDirectory($publicStorage)) {
        echo "   ✅ Removed successfully\n";
    } else {
        echo "   ⚠️  Could not remove completely\n";
    }
} else {
    echo "   Already doesn't exist\n";
}

echo "\n2. Running artisan storage:link...\n";
chdir(__DIR__);
exec('php artisan storage:link 2>&1', $output, $returnCode);
echo implode("\n", $output) . "\n";

if ($returnCode === 0 || strpos(implode('', $output), 'already exists') !== false) {
    echo "   ✅ Command executed\n";
}

echo "\n3. Verifying symlink...\n";
if (is_link($publicStorage) || file_exists($publicStorage . '/media')) {
    echo "   ✅ Storage is now accessible!\n";
    
    // Test the uploaded file
    $testPath = 'media/image/c2bxa0VzzduG8zgGxIVBlZJe3bZhlieGW71qVxO7.jpg';
    $fullPath = $publicStorage . '/' . $testPath;
    
    if (file_exists($fullPath)) {
        echo "   ✅ Your uploaded image is now accessible!\n";
        echo "   \n";
        echo "   Image URL:\n";
        echo "   http://127.0.0.1:8000/storage/$testPath\n";
    } else {
        echo "   ⚠️  Symlink works but file not found at expected location\n";
    }
} else {
    echo "   ❌ Still having issues\n";
    echo "   Try running this command manually as Administrator:\n";
    echo "   php artisan storage:link\n";
}

echo "\n==========================================\n";
echo "✅ DONE!\n";
echo "==========================================\n\n";
echo "Refresh your Media Library page (Ctrl+F5)!\n\n";
