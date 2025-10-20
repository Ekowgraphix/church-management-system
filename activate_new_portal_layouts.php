<?php
/**
 * Activate New Portal Layouts
 * This script will replace old portal layouts with new admin-styled versions
 * 
 * Run: php activate_new_portal_layouts.php
 */

echo "========================================\n";
echo "ACTIVATE NEW PORTAL LAYOUTS\n";
echo "========================================\n\n";

$baseDir = __DIR__ . '/resources/views/layouts';

$portals = [
    'pastor.blade.php',
    'ministry-leader.blade.php',
    'organization.blade.php',
    'volunteer.blade.php',
    'member-portal.blade.php'
];

echo "This script will activate the new admin-styled portal layouts.\n\n";
echo "Current status:\n";

foreach ($portals as $portal) {
    $oldFile = "$baseDir/$portal";
    $newFile = "$baseDir/$portal.new";
    $backupFile = "$baseDir/$portal.backup";
    
    echo "\n$portal:\n";
    echo "  - Old file: " . (file_exists($oldFile) ? '✅ EXISTS' : '❌ NOT FOUND') . "\n";
    echo "  - New file: " . (file_exists($newFile) ? '✅ EXISTS' : '❌ NOT FOUND') . "\n";
    echo "  - Backup: " . (file_exists($backupFile) ? '✅ EXISTS' : '❌ NOT FOUND') . "\n";
}

echo "\n========================================\n";
echo "OPTION 1: Activate all new layouts\n";
echo "========================================\n";
echo "This will:\n";
echo "1. Keep existing backups\n";
echo "2. Replace old layouts with new ones\n";
echo "3. Remove .new files\n\n";

echo "To activate, run:\n";
echo "php activate_new_portal_layouts.php --confirm\n\n";

if (isset($argv[1]) && $argv[1] === '--confirm') {
    echo "🔄 Activating new portal layouts...\n\n";
    
    foreach ($portals as $portal) {
        $oldFile = "$baseDir/$portal";
        $newFile = "$baseDir/$portal.new";
        
        if (file_exists($newFile)) {
            if (rename($newFile, $oldFile)) {
                echo "✅ $portal - Activated successfully\n";
            } else {
                echo "❌ $portal - Failed to activate\n";
            }
        } else {
            echo "⚠️  $portal - New file not found, skipped\n";
        }
    }
    
    echo "\n✅ Portal layout activation complete!\n";
    echo "========================================\n\n";
    
    echo "Next steps:\n";
    echo "1. Clear Laravel cache: php artisan cache:clear\n";
    echo "2. Clear view cache: php artisan view:clear\n";
    echo "3. Test each portal by logging in\n\n";
    
} else {
    echo "========================================\n";
    echo "OPTION 2: Manual activation\n";
    echo "========================================\n";
    echo "Manually review and rename each .new file:\n\n";
    
    foreach ($portals as $portal) {
        echo "rename \"resources\\views\\layouts\\$portal.new\" \"resources\\views\\layouts\\$portal\"\n";
    }
    
    echo "\n========================================\n";
    echo "To restore backups if needed:\n";
    echo "========================================\n\n";
    
    foreach ($portals as $portal) {
        echo "copy \"resources\\views\\layouts\\$portal.backup\" \"resources\\views\\layouts\\$portal\"\n";
    }
}

echo "\n✅ Script complete!\n";
?>
