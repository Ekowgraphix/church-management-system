<?php

echo "========================================\n";
echo "FIXING MOBILE DASHBOARD LAYOUT\n";
echo "========================================\n\n";

$layouts = [
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/member-portal.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/volunteer.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
];

$cleanMobileCSS = <<<'CSS'

        /* Clean Mobile Dashboard Layout */
        @media (max-width: 1024px) {
            /* Hide everything except hamburger and profile on mobile */
            .top-bar .space-x-6, .top-bar .space-x-4 {
                display: none !important;
            }
            
            .top-bar {
                padding: 0.75rem 1rem !important;
                min-height: 60px;
            }
            
            .mobile-menu-btn {
                width: 40px !important;
                height: 40px !important;
                margin-right: 0.5rem;
            }
            
            /* Top bar layout - just hamburger and profile */
            .top-bar > div {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                gap: 0.5rem;
            }
            
            .top-bar h1 {
                font-size: 1.25rem !important;
                margin: 0 !important;
                flex: 1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            .top-bar p {
                display: none !important;
            }
            
            /* Profile image smaller on mobile */
            .top-bar img, .top-bar > div > div > img {
                width: 35px !important;
                height: 35px !important;
                min-width: 35px !important;
            }
            
            .top-bar .gradient-green {
                width: 35px !important;
                height: 35px !important;
                min-width: 35px !important;
                font-size: 1rem !important;
            }
        }
        
        @media (max-width: 768px) {
            /* Dashboard cards full width on mobile */
            .grid-cols-2, .grid-cols-3, .grid-cols-4 {
                grid-template-columns: 1fr !important;
            }
            
            /* Reduce padding on cards */
            .rounded-lg, .glass-card, .bg-white {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }
            
            /* Text sizes */
            h1 { font-size: 1.25rem !important; }
            h2 { font-size: 1.1rem !important; }
            h3 { font-size: 1rem !important; }
            
            /* Buttons full width on mobile */
            .btn, button:not(.mobile-menu-btn) {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }
            
            /* Make tables scrollable */
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
        
        @media (max-width: 640px) {
            /* Extra small screens */
            body {
                font-size: 14px !important;
            }
            
            .top-bar h1 {
                font-size: 1.1rem !important;
            }
            
            main, .p-10, .p-8 {
                padding: 0.75rem !important;
            }
            
            /* Compact cards */
            .glass-card, .bg-white, .rounded-lg {
                padding: 0.75rem !important;
            }
        }
CSS;

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped: $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Check if already has clean mobile CSS
    if (strpos($content, 'Clean Mobile Dashboard Layout') !== false) {
        echo "✓ Already has clean layout: $layout\n";
        continue;
    }
    
    // Add clean mobile CSS before </style>
    $content = str_replace('    </style>', $cleanMobileCSS . "\n    </style>", $content);
    
    file_put_contents($layout, $content);
    echo "✓ Fixed mobile layout: $layout\n";
}

echo "\n========================================\n";
echo "COMPLETED!\n";
echo "========================================\n\n";
echo "Mobile dashboard now has:\n";
echo "✓ Clean header with just hamburger + title + profile\n";
echo "✓ Cards stack properly\n";
echo "✓ Better spacing\n";
echo "✓ Proper text sizes\n";
echo "\nRefresh your phone and it should look great!\n\n";
