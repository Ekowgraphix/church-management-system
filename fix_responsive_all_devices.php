<?php

$layouts = [
    'resources/views/layouts/member-portal.blade.php',
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
    'resources/views/layouts/volunteer.blade.php',
];

echo "==========================================\n";
echo "FIXING RESPONSIVE DESIGN FOR ALL DEVICES\n";
echo "==========================================\n\n";

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped (not found): $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    $changes = 0;
    
    // 1. Ensure proper viewport meta tag
    $oldViewport = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">';
    $newViewport = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
    
    if (strpos($content, 'maximum-scale=5.0') !== false) {
        $content = str_replace($oldViewport, $newViewport, $content);
        $changes++;
        echo "  ✓ Fixed viewport tag\n";
    }
    
    // 2. Remove conflicting responsive CSS if it exists
    if (strpos($content, '/* COMPREHENSIVE RESPONSIVE DESIGN */') !== false) {
        // Find and remove the old comprehensive responsive CSS
        $pattern = '/\/\* COMPREHENSIVE RESPONSIVE DESIGN \*\/.*?(?=\s*\/\*|<\/style>)/s';
        $content = preg_replace($pattern, '', $content);
        $changes++;
        echo "  ✓ Removed old responsive CSS\n";
    }
    
    // 3. Add proper responsive CSS before </style>
    $properResponsiveCSS = "
        /* ============================================
           PROPER RESPONSIVE DESIGN - AUTO DEVICE DETECTION
           ============================================ */
        
        /* Reset and Base Styles */
        * {
            box-sizing: border-box;
        }
        
        html {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }
        
        body {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar Base Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 320px;
            height: 100vh;
            z-index: 40;
            overflow-y: auto;
        }
        
        /* Main Content Base Styles */
        .main-content {
            min-height: 100vh;
            position: relative;
        }
        
        /* ============================================
           MOBILE FIRST - PHONES (Default < 1024px)
           ============================================ */
        
        /* Mobile: Sidebar hidden by default */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
            }
            
            body, html {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            /* Mobile: Show hamburger button */
            .mobile-menu-btn {
                display: flex !important;
            }
        }
        
        /* ============================================
           DESKTOP - LARGE SCREENS (≥ 1024px)
           ============================================ */
        
        @media (min-width: 1024px) {
            /* Desktop: Sidebar always visible */
            .sidebar {
                transform: translateX(0) !important;
                display: block !important;
            }
            
            /* Desktop: Content has margin for sidebar */
            .main-content {
                margin-left: 320px !important;
                width: calc(100% - 320px) !important;
            }
            
            /* Desktop: Hide hamburger button */
            .mobile-menu-btn {
                display: none !important;
            }
        }
        
        /* ============================================
           SPECIFIC MOBILE FIXES
           ============================================ */
        
        @media (max-width: 767px) {
            /* Extra small devices */
            .top-bar {
                padding: 0.75rem 1rem !important;
            }
            
            .top-bar h1 {
                font-size: 1.25rem !important;
            }
            
            /* Hide non-essential items in header */
            .top-bar .space-x-6 > button:not(.logout-form) {
                display: none !important;
            }
        }
";
    
    if (strpos($content, 'PROPER RESPONSIVE DESIGN - AUTO DEVICE DETECTION') === false) {
        $content = str_replace('    </style>', $properResponsiveCSS . "\n    </style>", $content);
        $changes++;
        echo "  ✓ Added proper responsive CSS\n";
    }
    
    if ($changes > 0) {
        file_put_contents($layout, $content);
        echo "✅ UPDATED: $layout ($changes changes)\n\n";
    } else {
        echo "✓ Already up to date: $layout\n\n";
    }
}

echo "==========================================\n";
echo "✅ ALL PORTALS FIXED!\n";
echo "==========================================\n\n";

echo "Responsive Design Features:\n";
echo "✓ Automatic device detection via media queries\n";
echo "✓ Mobile First approach (< 1024px)\n";
echo "✓ Desktop view (≥ 1024px)\n";
echo "✓ Proper viewport configuration\n";
echo "✓ No horizontal scrolling on mobile\n";
echo "✓ Sidebar auto-hides on mobile\n";
echo "✓ Sidebar always visible on desktop\n\n";

echo "IMPORTANT: Clear browser cache on all devices!\n\n";
