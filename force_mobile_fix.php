<?php

echo "========================================\n";
echo "FORCING MOBILE RESPONSIVE FIX\n";
echo "========================================\n\n";

$layouts = [
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/member-portal.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/volunteer.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/auth/login.blade.php',
];

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped: $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Ensure proper viewport meta tag
    $correctViewport = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">';
    
    // Replace any existing viewport tag
    if (preg_match('/<meta name="viewport"[^>]*>/', $content, $matches)) {
        $content = str_replace($matches[0], $correctViewport, $content);
        echo "✓ Updated viewport in: $layout\n";
    }
    
    // Add html and body base styles if not exists
    $baseStyles = <<<'CSS'

        /* FORCE MOBILE BASE STYLES */
        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
            margin: 0;
            padding: 0;
        }
        
        body {
            min-width: 320px;
        }
        
        *, *::before, *::after {
            box-sizing: border-box;
            max-width: 100%;
        }
CSS;

    // Add base styles right after opening <style> tag
    if (strpos($content, 'FORCE MOBILE BASE STYLES') === false) {
        $content = preg_replace('/(<style[^>]*>)/', '$1' . $baseStyles, $content, 1);
        echo "✓ Added base mobile styles to: $layout\n";
    }
    
    file_put_contents($layout, $content);
}

echo "\n========================================\n";
echo "FORCE APPLIED!\n";
echo "========================================\n\n";
echo "Now do these steps:\n";
echo "1. Clear phone browser cache completely\n";
echo "2. Close all browser tabs\n";
echo "3. Reopen browser\n";
echo "4. Go to: http://192.168.249.253:8000\n";
echo "5. Should work now!\n\n";
