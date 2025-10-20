<?php
/**
 * UPDATE ALL PORTAL LAYOUTS WITH ADMIN STYLING
 * 
 * This script automatically updates all 5 portal layouts
 * with the admin portal's professional CSS and structure
 * while keeping portal-specific navigation items.
 * 
 * Run: php update_all_portals_now.php
 */

echo "========================================\n";
echo "UPDATING ALL PORTAL LAYOUTS\n";
echo "========================================\n\n";

// Read the admin layout
$adminLayoutPath = __DIR__ . '/resources/views/layouts/app.blade.php';
if (!file_exists($adminLayoutPath)) {
    die("❌ Admin layout not found at: $adminLayoutPath\n");
}

$adminContent = file_get_contents($adminLayoutPath);
echo "✅ Admin layout loaded\n\n";

// Portal configurations
$portals = [
    [
        'file' => 'resources/views/layouts/pastor.blade.php',
        'name' => 'Pastor Portal',
        'subtitle' => 'Ministry Leadership Dashboard',
        'role' => 'Pastor',
        'nav' => [
            ['route' => 'pastor.dashboard', 'icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            ['route' => 'pastor.sermons', 'icon' => 'book-open', 'label' => 'Sermons', 'gradient' => 'blue'],
            ['route' => 'pastor.prayer-requests', 'icon' => 'praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'purple'],
            ['route' => 'pastor.members', 'icon' => 'users', 'label' => 'Members', 'gradient' => 'blue'],
            ['route' => 'pastor.events', 'icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            ['route' => 'pastor.counselling', 'icon' => 'hands-helping', 'label' => 'Counselling', 'gradient' => 'cyan'],
            ['route' => 'pastor.finance', 'icon' => 'dollar-sign', 'label' => 'Finance', 'gradient' => 'orange'],
            ['route' => 'pastor.broadcast', 'icon' => 'bullhorn', 'label' => 'Broadcast', 'gradient' => 'pink'],
            ['route' => 'pastor.ai-assistant', 'icon' => 'robot', 'label' => 'AI Assistant', 'gradient' => 'cyan'],
            ['route' => 'pastor.settings', 'icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    [
        'file' => 'resources/views/layouts/ministry-leader.blade.php',
        'name' => 'Ministry Leader',
        'subtitle' => 'Ministry Management',
        'role' => 'Ministry Leader',
        'nav' => [
            ['route' => 'ministry-leader.dashboard', 'icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            ['route' => 'ministry-leader.members', 'icon' => 'users', 'label' => 'Members', 'gradient' => 'blue'],
            ['route' => 'ministry-leader.groups', 'icon' => 'user-friends', 'label' => 'Small Groups', 'gradient' => 'purple'],
            ['route' => 'ministry-leader.events', 'icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            ['route' => 'ministry-leader.prayer-requests', 'icon' => 'praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'blue'],
            ['route' => 'ministry-leader.reports', 'icon' => 'chart-line', 'label' => 'Reports', 'gradient' => 'pink'],
            ['route' => 'ministry-leader.communication', 'icon' => 'comments', 'label' => 'Communication', 'gradient' => 'cyan'],
            ['route' => 'ministry-leader.ai-assistant', 'icon' => 'robot', 'label' => 'AI Assistant', 'gradient' => 'cyan'],
            ['route' => 'ministry-leader.settings', 'icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    [
        'file' => 'resources/views/layouts/organization.blade.php',
        'name' => 'Organization',
        'subtitle' => 'Multi-Branch Administration',
        'role' => 'Organization',
        'nav' => [
            ['route' => 'organization.dashboard', 'icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            ['route' => 'organization.branches', 'icon' => 'building', 'label' => 'Branches', 'gradient' => 'blue'],
            ['route' => 'organization.staff', 'icon' => 'users-cog', 'label' => 'Staff & Roles', 'gradient' => 'purple'],
            ['route' => 'organization.finance', 'icon' => 'dollar-sign', 'label' => 'Finance', 'gradient' => 'orange'],
            ['route' => 'organization.reports', 'icon' => 'chart-line', 'label' => 'Reports', 'gradient' => 'pink'],
            ['route' => 'organization.events', 'icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            ['route' => 'organization.ai-insights', 'icon' => 'brain', 'label' => 'AI Insights', 'gradient' => 'cyan'],
            ['route' => 'organization.communication', 'icon' => 'comments', 'label' => 'Communication', 'gradient' => 'blue'],
            ['route' => 'organization.settings', 'icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    [
        'file' => 'resources/views/layouts/volunteer.blade.php',
        'name' => 'Volunteer',
        'subtitle' => 'Your Service Dashboard',
        'role' => 'Volunteer',
        'nav' => [
            ['route' => 'volunteer.dashboard', 'icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            ['route' => 'volunteer.events', 'icon' => 'calendar-alt', 'label' => 'Assigned Events', 'gradient' => 'purple'],
            ['route' => 'volunteer.tasks', 'icon' => 'tasks', 'label' => 'My Tasks', 'gradient' => 'blue'],
            ['route' => 'volunteer.team', 'icon' => 'user-friends', 'label' => 'My Team', 'gradient' => 'cyan'],
            ['route' => 'volunteer.prayer', 'icon' => 'praying-hands', 'label' => 'Prayer', 'gradient' => 'purple'],
            ['route' => 'volunteer.ai-helper', 'icon' => 'robot', 'label' => 'AI Helper', 'gradient' => 'cyan'],
            ['route' => 'volunteer.communication', 'icon' => 'comments', 'label' => 'Communication', 'gradient' => 'pink'],
            ['route' => 'volunteer.settings', 'icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    [
        'file' => 'resources/views/layouts/member-portal.blade.php',
        'name' => 'Member Portal',
        'subtitle' => 'Your Church Dashboard',
        'role' => 'Church Member',
        'nav' => [
            ['route' => 'portal.index', 'icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            ['route' => 'portal.profile', 'icon' => 'user', 'label' => 'My Profile', 'gradient' => 'blue'],
            ['route' => 'events.index', 'icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            ['route' => 'portal.giving', 'icon' => 'hand-holding-usd', 'label' => 'My Giving', 'gradient' => 'orange'],
            ['route' => 'chat.index', 'icon' => 'comments', 'label' => 'Member Chat', 'gradient' => 'cyan'],
            ['route' => 'devotional.index', 'icon' => 'book-open', 'label' => 'Daily Devotional', 'gradient' => 'purple'],
            ['route' => 'prayer-requests.index', 'icon' => 'praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'pink'],
            ['route' => 'ai.chat', 'icon' => 'robot', 'label' => 'AI Chat', 'gradient' => 'cyan'],
            ['route' => 'notifications.index', 'icon' => 'bell', 'label' => 'Notifications', 'gradient' => 'orange'],
        ]
    ],
];

echo "📋 Configured portals: " . count($portals) . "\n\n";

foreach ($portals as $index => $portal) {
    echo ($index + 1) . ". Processing: {$portal['name']}\n";
    echo "   File: {$portal['file']}\n";
    echo "   Navigation items: " . count($portal['nav']) . "\n";
    
    // Start with admin content
    $portalContent = $adminContent;
    
    // Update title
    $portalContent = preg_replace(
        '/<title>.*?<\/title>/',
        '<title>' . $portal['name'] . ' - {{ config(\'app.name\') }}</title>',
        $portalContent
    );
    
    // Update logo section
    $portalContent = str_replace(
        '<h2 class="text-green-300 font-black text-2xl tracking-tight">ChurchMang</h2>',
        '<h2 class="text-green-300 font-black text-2xl tracking-tight">' . $portal['name'] . '</h2>',
        $portalContent
    );
    
    $portalContent = str_replace(
        '<p class="text-green-200 text-xs font-medium">Premium Edition</p>',
        '<p class="text-green-200 text-xs font-medium">' . $portal['subtitle'] . '</p>',
        $portalContent
    );
    
    // Update role display
    $portalContent = str_replace(
        '<p class="text-xs text-green-200">Administrator</p>',
        '<p class="text-xs text-green-200">' . $portal['role'] . '</p>',
        $portalContent
    );
    
    // Generate navigation HTML
    $navHtml = '';
    foreach ($portal['nav'] as $item) {
        $navHtml .= '                <a href="{{ route(\'' . $item['route'] . '\') }}" class="sidebar-item {{ request()->routeIs(\'' . $item['route'] . '\') ? \'active\' : \'\' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">' . "\n";
        $navHtml .= '                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs(\'' . $item['route'] . '\') ? \'gradient-' . $item['gradient'] . '\' : \'bg-white/5\' }} group-hover:gradient-' . $item['gradient'] . ' transition-all relative z-10">' . "\n";
        $navHtml .= '                        <i class="fas fa-' . $item['icon'] . ' text-white"></i>' . "\n";
        $navHtml .= '                    </div>' . "\n";
        $navHtml .= '                    <span class="ml-4 font-semibold text-sm relative z-10">' . $item['label'] . '</span>' . "\n";
        $navHtml .= '                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>' . "\n";
        $navHtml .= '                </a>' . "\n                \n";
    }
    
    // Replace navigation section (between the first <a href and </nav>)
    $portalContent = preg_replace(
        '/(<nav class="p-6 space-y-2 flex-1 sidebar-nav"[^>]*>)(.*?)(<\/nav>)/s',
        '$1' . "\n" . $navHtml . '            $3',
        $portalContent
    );
    
    // Write to file
    $filepath = __DIR__ . '/' . $portal['file'];
    if (file_put_contents($filepath, $portalContent)) {
        echo "   ✅ Updated successfully!\n";
    } else {
        echo "   ❌ Failed to update\n";
    }
    
    echo "\n";
}

echo "========================================\n";
echo "✅ ALL PORTAL LAYOUTS UPDATED!\n";
echo "========================================\n\n";

echo "Next steps:\n";
echo "1. Clear caches:\n";
echo "   php artisan cache:clear\n";
echo "   php artisan view:clear\n";
echo "   php artisan config:clear\n\n";

echo "2. Test each portal:\n";
foreach ($portals as $portal) {
    echo "   - {$portal['name']}\n";
}

echo "\n3. If issues occur, restore from backups:\n";
foreach ($portals as $portal) {
    echo "   copy \"{$portal['file']}.backup\" \"{$portal['file']}\"\n";
}

echo "\n✅ Portal styling update complete!\n";
echo "All portals now have the same professional design as the Admin portal.\n\n";
?>
