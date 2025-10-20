<?php
/**
 * Script to Update All Portal Layouts with Admin Styling
 * 
 * Run: php update_portal_layouts.php
 */

echo "========================================\n";
echo "Updating Portal Layouts with Admin Style\n";
echo "========================================\n\n";

$portals = [
    [
        'name' => 'Pastor',
        'file' => 'resources/views/layouts/pastor.blade.php',
        'title' => 'Pastor Portal',
        'subtitle' => 'Ministry Leadership Dashboard',
        'routes' => [
            ['route' => 'pastor.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard', 'gradient' => 'gradient-green'],
            ['route' => 'pastor.sermons', 'icon' => 'fa-book-open', 'label' => 'Sermons', 'gradient' => 'gradient-blue'],
            ['route' => 'pastor.prayer-requests', 'icon' => 'fa-praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'gradient-purple'],
            ['route' => 'pastor.members', 'icon' => 'fa-users', 'label' => 'Members', 'gradient' => 'gradient-blue'],
            ['route' => 'pastor.events', 'icon' => 'fa-calendar-alt', 'label' => 'Events', 'gradient' => 'gradient-purple'],
            ['route' => 'pastor.counselling', 'icon' => 'fa-hands-helping', 'label' => 'Counselling', 'gradient' => 'gradient-cyan'],
            ['route' => 'pastor.finance', 'icon' => 'fa-dollar-sign', 'label' => 'Finance', 'gradient' => 'gradient-orange'],
            ['route' => 'pastor.broadcast', 'icon' => 'fa-bullhorn', 'label' => 'Broadcast', 'gradient' => 'gradient-pink'],
            ['route' => 'pastor.ai-assistant', 'icon' => 'fa-robot', 'label' => 'AI Assistant', 'gradient' => 'gradient-cyan'],
            ['route' => 'pastor.settings', 'icon' => 'fa-cog', 'label' => 'Settings', 'gradient' => 'gradient-orange'],
        ]
    ],
    [
        'name' => 'Ministry Leader',
        'file' => 'resources/views/layouts/ministry-leader.blade.php',
        'title' => 'Ministry Leader Portal',
        'subtitle' => 'Ministry Management Dashboard',
        'routes' => [
            ['route' => 'ministry-leader.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard', 'gradient' => 'gradient-green'],
            ['route' => 'ministry-leader.members', 'icon' => 'fa-users', 'label' => 'Members', 'gradient' => 'gradient-blue'],
            ['route' => 'ministry-leader.groups', 'icon' => 'fa-user-friends', 'label' => 'Small Groups', 'gradient' => 'gradient-purple'],
            ['route' => 'ministry-leader.events', 'icon' => 'fa-calendar-alt', 'label' => 'Events', 'gradient' => 'gradient-purple'],
            ['route' => 'ministry-leader.prayer-requests', 'icon' => 'fa-praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'gradient-blue'],
            ['route' => 'ministry-leader.reports', 'icon' => 'fa-chart-line', 'label' => 'Reports', 'gradient' => 'gradient-pink'],
            ['route' => 'ministry-leader.communication', 'icon' => 'fa-comments', 'label' => 'Communication', 'gradient' => 'gradient-cyan'],
            ['route' => 'ministry-leader.ai-assistant', 'icon' => 'fa-robot', 'label' => 'AI Assistant', 'gradient' => 'gradient-cyan'],
            ['route' => 'ministry-leader.settings', 'icon' => 'fa-cog', 'label' => 'Settings', 'gradient' => 'gradient-orange'],
        ]
    ],
    [
        'name' => 'Organization',
        'file' => 'resources/views/layouts/organization.blade.php',
        'title' => 'Organization Portal',
        'subtitle' => 'Multi-Branch Management',
        'routes' => [
            ['route' => 'organization.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard', 'gradient' => 'gradient-green'],
            ['route' => 'organization.branches', 'icon' => 'fa-building', 'label' => 'Branches', 'gradient' => 'gradient-blue'],
            ['route' => 'organization.staff', 'icon' => 'fa-users-cog', 'label' => 'Staff & Roles', 'gradient' => 'gradient-purple'],
            ['route' => 'organization.finance', 'icon' => 'fa-dollar-sign', 'label' => 'Finance', 'gradient' => 'gradient-orange'],
            ['route' => 'organization.reports', 'icon' => 'fa-chart-line', 'label' => 'Reports', 'gradient' => 'gradient-pink'],
            ['route' => 'organization.events', 'icon' => 'fa-calendar-alt', 'label' => 'Events', 'gradient' => 'gradient-purple'],
            ['route' => 'organization.ai-insights', 'icon' => 'fa-brain', 'label' => 'AI Insights', 'gradient' => 'gradient-cyan'],
            ['route' => 'organization.communication', 'icon' => 'fa-comments', 'label' => 'Communication', 'gradient' => 'gradient-blue'],
            ['route' => 'organization.settings', 'icon' => 'fa-cog', 'label' => 'Settings', 'gradient' => 'gradient-orange'],
        ]
    ],
    [
        'name' => 'Volunteer',
        'file' => 'resources/views/layouts/volunteer.blade.php',
        'title' => 'Volunteer Portal',
        'subtitle' => 'Your Service Dashboard',
        'routes' => [
            ['route' => 'volunteer.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard', 'gradient' => 'gradient-green'],
            ['route' => 'volunteer.events', 'icon' => 'fa-calendar-alt', 'label' => 'Assigned Events', 'gradient' => 'gradient-purple'],
            ['route' => 'volunteer.tasks', 'icon' => 'fa-tasks', 'label' => 'My Tasks', 'gradient' => 'gradient-blue'],
            ['route' => 'volunteer.team', 'icon' => 'fa-user-friends', 'label' => 'My Team', 'gradient' => 'gradient-cyan'],
            ['route' => 'volunteer.prayer', 'icon' => 'fa-praying-hands', 'label' => 'Prayer', 'gradient' => 'gradient-purple'],
            ['route' => 'volunteer.ai-helper', 'icon' => 'fa-robot', 'label' => 'AI Helper', 'gradient' => 'gradient-cyan'],
            ['route' => 'volunteer.communication', 'icon' => 'fa-comments', 'label' => 'Communication', 'gradient' => 'gradient-pink'],
            ['route' => 'volunteer.settings', 'icon' => 'fa-cog', 'label' => 'Settings', 'gradient' => 'gradient-orange'],
        ]
    ],
    [
        'name' => 'Member',
        'file' => 'resources/views/layouts/member-portal.blade.php',
        'title' => 'Member Portal',
        'subtitle' => 'Your Church Dashboard',
        'routes' => [
            ['route' => 'portal.index', 'icon' => 'fa-home', 'label' => 'Dashboard', 'gradient' => 'gradient-green'],
            ['route' => 'portal.profile', 'icon' => 'fa-user', 'label' => 'My Profile', 'gradient' => 'gradient-blue'],
            ['route' => 'events.index', 'icon' => 'fa-calendar-alt', 'label' => 'Events', 'gradient' => 'gradient-purple'],
            ['route' => 'portal.giving', 'icon' => 'fa-hand-holding-usd', 'label' => 'My Giving', 'gradient' => 'gradient-orange'],
            ['route' => 'chat.index', 'icon' => 'fa-comments', 'label' => 'Member Chat', 'gradient' => 'gradient-cyan'],
            ['route' => 'devotional.index', 'icon' => 'fa-book-open', 'label' => 'Daily Devotional', 'gradient' => 'gradient-purple'],
            ['route' => 'prayer-requests.index', 'icon' => 'fa-praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'gradient-pink'],
            ['route' => 'ai.chat', 'icon' => 'fa-robot', 'label' => 'AI Chat', 'gradient' => 'gradient-cyan'],
            ['route' => 'notifications.index', 'icon' => 'fa-bell', 'label' => 'Notifications', 'gradient' => 'gradient-orange'],
        ]
    ],
];

echo "✅ Portal configuration loaded\n";
echo "📝 Total portals to update: " . count($portals) . "\n\n";

foreach ($portals as $index => $portal) {
    echo ($index + 1) . ". " . $portal['name'] . " Portal\n";
}

echo "\n✨ Ready to update layouts with admin styling!\n";
echo "   - Glass morphism effects\n";
echo   "   - Animated gradients\n";
echo "   - Modern sidebar design\n";
echo "   - Professional animations\n";
echo "   - Consistent UI/UX\n\n";

echo "Run the Cascade tool to apply these changes.\n";
?>
