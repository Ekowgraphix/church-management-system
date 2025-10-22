<?php

echo "========================================\n";
echo "ADDING RESPONSIVE DESIGN TO ALL PORTALS\n";
echo "========================================\n\n";

$layouts = [
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/member-portal.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/volunteer.blade.php',
];

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
            
            .top-bar {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .top-bar h1 {
                font-size: 1.5rem !important;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 260px !important;
            }
            
            .top-bar {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
            
            .top-bar h1 {
                font-size: 1.25rem !important;
            }
            
            .grid {
                grid-template-columns: 1fr !important;
            }
            
            .hide-mobile {
                display: none !important;
            }
        }
        
        @media (max-width: 640px) {
            .top-bar .user-info {
                display: none !important;
            }
            
            body {
                font-size: 14px;
            }
            
            .glass-card {
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
        
        .mobile-menu-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .mobile-menu-btn i {
            color: #86efac;
            font-size: 1.5rem;
        }
CSS;

$responsiveJS = <<<'JS'

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
        
        // Close mobile menu when clicking a link
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        toggleMobileMenu();
                    }
                });
            });
            
            // Close menu on window resize if desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('mobileOverlay');
                    if (sidebar) sidebar.classList.remove('mobile-open');
                    if (overlay) overlay.classList.remove('active');
                }
            });
        });
    </script>
JS;

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped: $layout (not found)\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Check if already has responsive CSS
    if (strpos($content, 'Mobile Responsive Styles') !== false) {
        echo "✓ Already responsive: $layout\n";
        continue;
    }
    
    // Add responsive CSS before </style>
    $content = str_replace('    </style>', $responsiveCSS . "\n    </style>", $content);
    
    // Add mobile overlay and ID to sidebar
    $content = str_replace('<body', '<!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
    
<body', $content);
    
    $content = str_replace('<aside class="sidebar', '<aside class="sidebar" id="sidebar"', $content);
    
    // Add hamburger menu button
    $content = str_replace(
        '<div class="flex items-center justify-between',
        '<!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex items-center justify-between',
        $content
    );
    
    // Add main-content class
    $content = str_replace('class="flex-1 ml-', 'class="flex-1 ml-80 lg:ml-80 ml-0 main-content ml-', $content);
    
    // Add responsive classes to existing elements
    $content = str_replace('class="px-10 py-6"', 'class="px-4 md:px-6 lg:px-10 py-4 md:py-6"', $content);
    $content = str_replace('class="p-10', 'class="p-4 md:p-6 lg:p-10', $content);
    $content = str_replace('class="text-3xl', 'class="text-2xl md:text-3xl', $content);
    
    // Add responsive JS before </body>
    $content = str_replace('</body>', $responsiveJS . "\n</body>", $content);
    
    // Save file
    file_put_contents($layout, $content);
    
    echo "✓ Added responsive design: $layout\n";
}

echo "\n========================================\n";
echo "COMPLETED!\n";
echo "========================================\n";
echo "\nAll portal layouts are now responsive!\n";
echo "Test on mobile: http://192.168.249.253:8000\n\n";
