<?php

echo "Checking upload directories...\n\n";

$directories = [
    'public/uploads/sermons',
    'public/uploads/profiles',
    'public/uploads/members',
    'public/uploads/events',
    'public/uploads/documents',
    'storage/app/public',
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "✓ Created: $dir\n";
    } else {
        echo "✓ Exists: $dir\n";
    }
}

echo "\n✅ All upload directories verified!\n";
