<?php

echo "========================================\n";
echo "FIXING MOBILE FIT ISSUES\n";
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

$mobileFixCSS = <<<'CSS'

        /* Aggressive Mobile Fit CSS */
        * {
            box-sizing: border-box !important;
        }
        
        html {
            overflow-x: hidden;
            width: 100%;
        }
        
        body {
            overflow-x: hidden !important;
            width: 100% !important;
            max-width: 100vw !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        @media (max-width: 1024px) {
            body, html {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            .sidebar {
                position: fixed !important;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 50 !important;
            }
            
            main, .main-content, .content-area {
                width: 100% !important;
                max-width: 100vw !important;
                margin-left: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                overflow-x: hidden !important;
            }
            
            .container, .max-w-7xl, .w-full {
                max-width: 100% !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            
            table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
        
        @media (max-width: 768px) {
            .top-bar, .header {
                padding: 1rem !important;
                flex-wrap: wrap;
            }
            
            h1 {
                font-size: 1.5rem !important;
                word-wrap: break-word;
            }
            
            h2 {
                font-size: 1.25rem !important;
            }
            
            h3 {
                font-size: 1.1rem !important;
            }
            
            .card, .glass-card, .bg-white {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding: 1rem !important;
            }
            
            .grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            button, .btn, a.btn {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            input, textarea, select {
                width: 100% !important;
                font-size: 16px !important;
            }
        }
        
        @media (max-width: 640px) {
            body {
                font-size: 14px !important;
            }
            
            .p-10, .p-8, .p-6 {
                padding: 1rem !important;
            }
            
            .px-10, .px-8, .px-6 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            .text-3xl {
                font-size: 1.25rem !important;
            }
            
            .text-2xl {
                font-size: 1.125rem !important;
            }
            
            .space-x-6 > * + *, .space-x-4 > * + * {
                margin-left: 0.5rem !important;
            }
            
            .gap-6 {
                gap: 0.75rem !important;
            }
        }
CSS;

$count = 0;

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped: $layout (not found)\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Check if already has aggressive mobile fix
    if (strpos($content, 'Aggressive Mobile Fit CSS') !== false) {
        echo "✓ Already has mobile fix: $layout\n";
        continue;
    }
    
    // Add aggressive mobile fix CSS before the closing </style> tag
    if (strpos($content, '</style>') !== false) {
        $content = str_replace('    </style>', $mobileFixCSS . "\n    </style>", $content);
        file_put_contents($layout, $content);
        echo "✓ Added mobile fit CSS: $layout\n";
        $count++;
    } else {
        echo "⚠ No </style> tag found in: $layout\n";
    }
}

echo "\n========================================\n";
echo "COMPLETED!\n";
echo "========================================\n";
echo "\nUpdated $count file(s)\n";
echo "\nNext steps:\n";
echo "1. Restart server: start_server_network.bat\n";
echo "2. Open on phone: http://192.168.249.253:8000\n";
echo "3. Hard refresh (pull down)\n";
echo "4. Should fit perfectly now!\n\n";
