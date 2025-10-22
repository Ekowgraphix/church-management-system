<?php

$layouts = [
    'resources/views/layouts/member-portal.blade.php',
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
    'resources/views/layouts/volunteer.blade.php',
];

echo "==========================================\n";
echo "AGGRESSIVE MOBILE FIX - OVERRIDE TAILWIND\n";
echo "==========================================\n\n";

$aggressiveCSS = "
        /* ============================================
           AGGRESSIVE MOBILE FIX - OVERRIDE EVERYTHING
           ============================================ */
        
        /* Force full width on mobile - Override Tailwind */
        @media (max-width: 1023px) {
            
            /* Force main content to be full width on mobile */
            .main-content,
            div.main-content,
            .flex-1.main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                left: 0 !important;
                right: 0 !important;
                position: relative !important;
            }
            
            /* Force body and html to prevent horizontal scroll */
            html, body {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* Force all containers inside main-content to be full width */
            .main-content > * {
                max-width: 100vw !important;
            }
            
            /* Force the top bar to be full width */
            .top-bar {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                left: 0 !important;
                right: 0 !important;
            }
            
            /* Force main tag to be full width */
            main {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            /* Hide sidebar completely on mobile */
            .sidebar {
                display: none !important;
            }
            
            .sidebar.show {
                display: block !important;
            }
        }
";

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped (not found): $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Check if already has aggressive fix
    if (strpos($content, 'AGGRESSIVE MOBILE FIX - OVERRIDE EVERYTHING') !== false) {
        echo "✓ Already has aggressive fix: $layout\n";
        continue;
    }
    
    // Add before </style>
    if (strpos($content, '</style>') !== false) {
        $content = str_replace('    </style>', $aggressiveCSS . "\n    </style>", $content);
        file_put_contents($layout, $content);
        echo "✅ Added aggressive mobile fix: $layout\n";
    } else {
        echo "⚠ Could not find </style> tag in: $layout\n";
    }
}

echo "\n==========================================\n";
echo "✅ AGGRESSIVE FIXES APPLIED!\n";
echo "==========================================\n\n";

echo "Added fixes:\n";
echo "✓ Force margin-left: 0 on mobile\n";
echo "✓ Force width: 100vw on mobile\n";
echo "✓ Override ALL Tailwind classes\n";
echo "✓ Prevent horizontal scroll\n";
echo "✓ Hide sidebar completely\n";
echo "✓ Full width top bar\n";
echo "✓ All with !important flags\n\n";

echo "⚠️  CRITICAL: Clear cache on mobile device!\n";
echo "Settings → Apps → Browser → Clear cache\n";
echo "Then force close browser and reopen!\n\n";
