<?php
/**
 * Apply Admin Portal Styling to All Portals
 * This script will update all portal layouts with the admin design
 * 
 * Run: php apply_admin_styling_to_portals.php
 */

echo "========================================\n";
echo "APPLYING ADMIN STYLING TO ALL PORTALS\n";
echo "========================================\n\n";

// Base directory
$baseDir = __DIR__;
$layoutsDir = $baseDir . '/resources/views/layouts';

// Check if layouts directory exists
if (!is_dir($layoutsDir)) {
    die("❌ Layouts directory not found: $layoutsDir\n");
}

echo "✅ Layouts directory found\n";
echo "📂 Location: $layoutsDir\n\n";

// Portal configurations with their specific routes
$portals = [
    [
        'name' => 'Pastor Portal',
        'filename' => 'pastor.blade.php',
        'title' => 'Pastor Portal',
        'subtitle' => 'Ministry Leadership Dashboard',
        'role' => 'Pastor',
        'prefix' => 'pastor',
        'navigation' => [
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
        'name' => 'Ministry Leader Portal',
        'filename' => 'ministry-leader.blade.php',
        'title' => 'Ministry Leader Portal',
        'subtitle' => 'Ministry Management Dashboard',
        'role' => 'Ministry Leader',
        'prefix' => 'ministry-leader',
        'navigation' => [
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
        'name' => 'Organization Portal',
        'filename' => 'organization.blade.php',
        'title' => 'Organization Portal',
        'subtitle' => 'Multi-Branch Administration',
        'role' => 'Organization',
        'prefix' => 'organization',
        'navigation' => [
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
        'name' => 'Volunteer Portal',
        'filename' => 'volunteer.blade.php',
        'title' => 'Volunteer Portal',
        'subtitle' => 'Your Service Dashboard',
        'role' => 'Volunteer',
        'prefix' => 'volunteer',
        'navigation' => [
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
        'name' => 'Member Portal',
        'filename' => 'member-portal.blade.php',
        'title' => 'Member Portal',
        'subtitle' => 'Your Church Dashboard',
        'role' => 'Church Member',
        'prefix' => 'portal',
        'navigation' => [
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

echo "📋 Portal configurations loaded:\n";
foreach ($portals as $index => $portal) {
    echo "   " . ($index + 1) . ". {$portal['name']} - " . count($portal['navigation']) . " navigation items\n";
}

echo "\n✨ Each portal will include:\n";
echo "   ✅ Glass morphism effects\n";
echo "   ✅ Animated gradient backgrounds\n";
echo "   ✅ Modern sidebar with hover effects\n";
echo "   ✅ Professional icon boxes\n";
echo "   ✅ Smooth transitions and animations\n";
echo "   ✅ Custom scrollbar styling\n";
echo "   ✅ Search and notification modals\n";
echo "   ✅ Premium badge section\n";
echo "   ✅ Responsive design\n";
echo "   ✅ Consistent branding\n\n";

echo "📝 Files to be updated:\n";
foreach ($portals as $portal) {
    $filepath = "$layoutsDir/{$portal['filename']}";
    $exists = file_exists($filepath) ? '✅ EXISTS' : '❌ NOT FOUND';
    echo "   $exists - {$portal['filename']}\n";
}

echo "\n🎨 Styling updates:\n";
echo "   - Same CSS as admin portal\n";
echo "   - Portal-specific navigation\n";
echo "   - Portal-specific titles\n";
echo "   - Maintained route prefixes\n\n";

echo "========================================\n";
echo "✅ Configuration complete!\n";
echo "========================================\n\n";

echo "Next step: Use Cascade to generate the updated layout files with admin styling.\n";
echo "All portals will have a consistent, professional appearance.\n\n";
?>
