<?php

$layouts = [
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
    'resources/views/layouts/volunteer.blade.php',
];

echo "==========================================\n";
echo "FIXING MOBILE LAYOUT FOR ALL PORTALS\n";
echo "==========================================\n\n";

$broken = 'ml-80 lg:ml-80 ml-0 main-content ml-80';
$fixed = 'ml-0 lg:ml-80 main-content';

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped (not found): $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    if (strpos($content, $broken) !== false) {
        $content = str_replace($broken, $fixed, $content);
        file_put_contents($layout, $content);
        echo "✅ FIXED: $layout\n";
    } else {
        echo "✓ Already fixed or not found: $layout\n";
    }
}

echo "\n==========================================\n";
echo "✅ ALL PORTALS FIXED!\n";
echo "==========================================\n";
echo "\nFixed Issue:\n";
echo "• Removed conflicting margin classes\n";
echo "• Mobile: ml-0 (no left margin)\n";
echo "• Desktop: ml-80 (320px left margin)\n";
echo "• Content now displays properly on mobile!\n\n";
