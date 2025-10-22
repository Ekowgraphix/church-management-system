<?php

$layouts = [
    'resources/views/layouts/pastor.blade.php',
    'resources/views/layouts/organization.blade.php',
    'resources/views/layouts/ministry-leader.blade.php',
    'resources/views/layouts/volunteer.blade.php',
];

echo "==========================================\n";
echo "ADDING PROMINENT LOGOUT BUTTONS TO ALL PORTALS\n";
echo "==========================================\n\n";

// Header logout button replacement
$oldHeaderLogout = '<form method="POST" action="{{ route(\'logout\') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-12 h-12 bg-white/5 hover:bg-red-500/20 rounded-xl flex items-center justify-center text-green-300 hover:text-red-400 transition-all">
                                    <i class="fas fa-sign-out-alt text-lg"></i>
                                </button>
                            </form>';

$newHeaderLogout = '</div>
                        
                        <!-- Logout Button - Always Visible -->
                        <form method="POST" action="{{ route(\'logout\') }}" class="inline logout-form">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center space-x-2 px-4 py-2.5 bg-red-500/20 hover:bg-red-500 rounded-xl text-red-400 hover:text-white transition-all border border-red-500/30 hover:border-red-500 shadow-lg hover:shadow-red-500/50"
                                    onclick="return confirm(\'Are you sure you want to logout?\')">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                                <span class="font-semibold text-sm">Logout</span>
                            </button>
                        </form>';

foreach ($layouts as $layout) {
    if (!file_exists($layout)) {
        echo "⚠ Skipped (not found): $layout\n";
        continue;
    }
    
    $content = file_get_contents($layout);
    
    // Check if already updated
    if (strpos($content, 'logout-form') !== false) {
        echo "✓ Already has prominent logout: $layout\n";
        continue;
    }
    
    // Replace header logout button
    if (strpos($content, $oldHeaderLogout) !== false) {
        // Replace the old logout form and close the previous div
        $content = str_replace(
            '                            @endif' . "\n" . '                            ' . $oldHeaderLogout,
            '                            @endif' . "\n" . '                        ' . $newHeaderLogout,
            $content
        );
        
        echo "✓ Updated header logout: $layout\n";
    } else {
        echo "⚠ Could not find header logout pattern in: $layout\n";
    }
    
    // Add logout button to sidebar (before Premium Badge section)
    $sidebarPattern = '            <!-- Premium Badge - Fixed at bottom -->';
    $sidebarReplacement = '            <!-- Logout Button in Sidebar - Fixed at bottom -->
            <div class="p-6 border-t border-white border-opacity-10 flex-shrink-0 space-y-3">
                <!-- Logout Button -->
                <form method="POST" action="{{ route(\'logout\') }}" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-500/20 hover:bg-red-500 rounded-xl text-red-400 hover:text-white transition-all border border-red-500/30 hover:border-red-500 group"
                            onclick="return confirm(\'Are you sure you want to logout?\')">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span class="font-semibold text-sm">Logout</span>
                    </button>
                </form>
                
            <!-- Premium Badge - Fixed at bottom -->';
    
    if (strpos($content, $sidebarPattern) !== false && strpos($content, '<!-- Logout Button in Sidebar') === false) {
        $content = str_replace($sidebarPattern, $sidebarReplacement, $content);
        echo "✓ Added sidebar logout: $layout\n";
    }
    
    // Add CSS for logout button visibility
    $cssPattern = '        /* Clean Mobile Dashboard Layout */';
    $cssAddition = '        /* Logout Button Always Visible */
        .logout-form {
            display: inline-block !important;
        }
        
        .logout-form button {
            white-space: nowrap;
        }
        
        /* Clean Mobile Dashboard Layout */';
    
    if (strpos($content, '/* Logout Button Always Visible */') === false && strpos($content, $cssPattern) !== false) {
        $content = str_replace($cssPattern, $cssAddition, $content);
        echo "✓ Added logout CSS: $layout\n";
    }
    
    // Add mobile logout styles
    $mobileCssPattern = '            /* Hide everything except hamburger and profile on mobile */';
    $mobileCssAddition = '            /* Hide everything except hamburger, profile, and logout on mobile */
            .top-bar .space-x-6 > *:not(.logout-form) {
                display: none !important;
            }
            
            /* Mobile logout button - icon only */
            .logout-form button span {
                display: none;
            }
            
            .logout-form button {
                padding: 0.5rem !important;
                min-width: 40px;
                justify-content: center;
            }
            
            /* Hide everything except hamburger and profile on mobile (legacy) */';
    
    if (strpos($content, 'Mobile logout button - icon only') === false && strpos($content, $mobileCssPattern) !== false) {
        $content = str_replace($mobileCssPattern, $mobileCssAddition, $content);
        echo "✓ Added mobile logout CSS: $layout\n";
    }
    
    file_put_contents($layout, $content);
    echo "✅ COMPLETED: $layout\n\n";
}

echo "==========================================\n";
echo "✅ ALL PORTALS UPDATED!\n";
echo "==========================================\n";
echo "\nLogout buttons added to:\n";
echo "• Header (top right)\n";
echo "• Sidebar (bottom)\n";
echo "\nFeatures:\n";
echo "✓ Prominent red color\n";
echo "✓ Text + icon (desktop)\n";
echo "✓ Icon only (mobile)\n";
echo "✓ Confirmation dialog\n";
echo "✓ Hover effects\n\n";
