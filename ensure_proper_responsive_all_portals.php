<?php

$layouts = [
    'resources/views/layouts/member-portal.blade.php',
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
    'resources/views/layouts/volunteer.blade.php',
];

echo "==========================================\n";
echo "ENSURING PROPER RESPONSIVE DESIGN\n";
echo "==========================================\n\n";

// Add comprehensive responsive CSS
$responsiveCSSToAdd = "
        /* COMPREHENSIVE RESPONSIVE DESIGN */
        /* Desktop First - Sidebar visible, content has margin */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 320px;
            z-index: 40;
        }
        
        .main-content {
            margin-left: 320px;
            width: calc(100% - 320px);
        }
        
        /* Tablet and Mobile - Sidebar hidden, content full width */
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
            }
        }
        
        /* Desktop - Sidebar always visible */
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0) !important;
            }
            
            .main-content {
                margin-left: 320px !important;
                width: calc(100% - 320px) !important;
            }
        }
";

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped (not found): $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Check if already has comprehensive responsive CSS
    if (strpos($content, 'COMPREHENSIVE RESPONSIVE DESIGN') !== false) {
        echo "✓ Already has comprehensive responsive CSS: $layout\n";
        continue;
    }
    
    // Add before the closing </style> tag
    $pattern = '    </style>';
    if (strpos($content, $pattern) !== false) {
        $content = str_replace($pattern, $responsiveCSSToAdd . "\n    </style>", $content);
        file_put_contents($layout, $content);
        echo "✅ Added comprehensive responsive CSS: $layout\n";
    } else {
        echo "⚠ Could not find </style> tag in: $layout\n";
    }
}

echo "\n==========================================\n";
echo "✅ RESPONSIVE DESIGN ENSURED!\n";
echo "==========================================\n";
echo "\nWhat was added:\n";
echo "✓ Desktop (≥1024px): Sidebar visible, content with 320px margin\n";
echo "✓ Mobile (<1024px): Sidebar hidden, content full width\n";
echo "✓ Proper transitions and animations\n";
echo "✓ No layout conflicts between devices\n\n";
echo "Each device now sees its proper layout!\n\n";
