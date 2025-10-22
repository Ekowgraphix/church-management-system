<?php

$layouts = [
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
    'resources/views/layouts/volunteer.blade.php',
];

echo "==========================================\n";
echo "REMOVING TAILWIND MARGIN CLASSES\n";
echo "==========================================\n\n";

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped (not found): $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Remove ml-0 lg:ml-80 and similar patterns
    $patterns = [
        'class="flex-1 ml-0 lg:ml-80 main-content' => 'class="flex-1 main-content',
        'class="flex-1 lg:ml-80 main-content' => 'class="flex-1 main-content',
        'class="flex-1 ml-80 main-content' => 'class="flex-1 main-content',
    ];
    
    $changed = false;
    foreach ($patterns as $old => $new) {
        if (strpos($content, $old) !== false) {
            $content = str_replace($old, $new, $content);
            $changed = true;
            echo "  ✓ Removed Tailwind margin classes\n";
        }
    }
    
    if ($changed) {
        file_put_contents($layout, $content);
        echo "✅ UPDATED: $layout\n\n";
    } else {
        echo "✓ Already clean: $layout\n\n";
    }
}

echo "==========================================\n";
echo "✅ ALL TAILWIND MARGINS REMOVED!\n";
echo "==========================================\n\n";

echo "Now CSS media queries fully control the layout!\n";
echo "No Tailwind classes to interfere!\n\n";
