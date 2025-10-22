<?php

$file = 'resources/views/layouts/app.blade.php';

if (!file_exists($file)) {
    die("File not found: $file\n");
}

$content = file_get_contents($file);

if (strpos($content, 'Mobile Responsive') !== false) {
    die("✓ Already responsive: $file\n");
}

$responsiveCSS = <<<'CSS'

        /* Mobile Responsive Styles */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                width: 280px !important;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .mobile-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 35;
                backdrop-filter: blur(4px);
            }
            
            .mobile-overlay.active {
                display: block;
            }
            
            .mobile-menu-btn {
                display: flex !important;
            }
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr !important;
            }
            
            body {
                font-size: 14px;
            }
        }
        
        @media (max-width: 640px) {
            .glass-card, .card {
                padding: 1rem !important;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
CSS;

// Add responsive CSS
$content = str_replace('    </style>', $responsiveCSS . "\n    </style>", $content);

file_put_contents($file, $content);

echo "✓ Added responsive design to: $file\n";
echo "All layouts are now responsive!\n";
