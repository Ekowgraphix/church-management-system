<?php
/**
 * Generate Updated Portal Layouts with Admin Styling
 * Run: php generate_portal_layouts.php
 */

echo "==============================================\n";
echo "Generating Portal Layouts with Admin Styling\n";
echo "==============================================\n\n";

// Read the base admin layout for CSS
$adminLayoutPath = __DIR__ . '/resources/views/layouts/app.blade.php';
if (!file_exists($adminLayoutPath)) {
    die("❌ Admin layout not found!\n");
}

$adminContent = file_get_contents($adminLayoutPath);

// Extract CSS from admin layout (between <style> tags)
preg_match('/<style>(.*?)<\/style>/s', $adminContent, $matches);
$sharedCSS = $matches[1] ?? '';

echo "✅ Extracted admin portal CSS\n";

// Portal configurations
$portals = [
    'pastor' => [
        'title' => 'Pastor Portal',
        'subtitle' => 'Ministry Leadership Dashboard',
        'role' => 'Pastor',
        'routes' => [
            "Route::get('dashboard', 'dashboard')->name('dashboard')" => ['icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            "Route::get('sermons', 'sermons')->name('sermons')" => ['icon' => 'book-open', 'label' => 'Sermons', 'gradient' => 'blue'],
            "Route::get('prayer-requests', 'prayerRequests')->name('prayer-requests')" => ['icon' => 'praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'purple'],
            "Route::get('members', 'members')->name('members')" => ['icon' => 'users', 'label' => 'Members', 'gradient' => 'blue'],
            "Route::get('events', 'events')->name('events')" => ['icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            "Route::get('counselling', 'counselling')->name('counselling')" => ['icon' => 'hands-helping', 'label' => 'Counselling', 'gradient' => 'cyan'],
            "Route::get('finance', 'finance')->name('finance')" => ['icon' => 'dollar-sign', 'label' => 'Finance', 'gradient' => 'orange'],
            "Route::get('broadcast', 'broadcast')->name('broadcast')" => ['icon' => 'bullhorn', 'label' => 'Broadcast', 'gradient' => 'pink'],
            "Route::get('ai-assistant', 'aiAssistant')->name('ai-assistant')" => ['icon' => 'robot', 'label' => 'AI Assistant', 'gradient' => 'cyan'],
            "Route::get('settings', 'settings')->name('settings')" => ['icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    'ministry-leader' => [
        'title' => 'Ministry Leader Portal',
        'subtitle' => 'Ministry Management Dashboard',
        'role' => 'Ministry Leader',
        'routes' => [
            'ministry-leader.dashboard' => ['icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            'ministry-leader.members' => ['icon' => 'users', 'label' => 'Members', 'gradient' => 'blue'],
            'ministry-leader.groups' => ['icon' => 'user-friends', 'label' => 'Small Groups', 'gradient' => 'purple'],
            'ministry-leader.events' => ['icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            'ministry-leader.prayer-requests' => ['icon' => 'praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'blue'],
            'ministry-leader.reports' => ['icon' => 'chart-line', 'label' => 'Reports', 'gradient' => 'pink'],
            'ministry-leader.communication' => ['icon' => 'comments', 'label' => 'Communication', 'gradient' => 'cyan'],
            'ministry-leader.ai-assistant' => ['icon' => 'robot', 'label' => 'AI Assistant', 'gradient' => 'cyan'],
            'ministry-leader.settings' => ['icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    'organization' => [
        'title' => 'Organization Portal',
        'subtitle' => 'Multi-Branch Administration',
        'role' => 'Organization',
        'routes' => [
            'organization.dashboard' => ['icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            'organization.branches' => ['icon' => 'building', 'label' => 'Branches', 'gradient' => 'blue'],
            'organization.staff' => ['icon' => 'users-cog', 'label' => 'Staff & Roles', 'gradient' => 'purple'],
            'organization.finance' => ['icon' => 'dollar-sign', 'label' => 'Finance', 'gradient' => 'orange'],
            'organization.reports' => ['icon' => 'chart-line', 'label' => 'Reports', 'gradient' => 'pink'],
            'organization.events' => ['icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            'organization.ai-insights' => ['icon' => 'brain', 'label' => 'AI Insights', 'gradient' => 'cyan'],
            'organization.communication' => ['icon' => 'comments', 'label' => 'Communication', 'gradient' => 'blue'],
            'organization.settings' => ['icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    'volunteer' => [
        'title' => 'Volunteer Portal',
        'subtitle' => 'Your Service Dashboard',
        'role' => 'Volunteer',
        'routes' => [
            'volunteer.dashboard' => ['icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            'volunteer.events' => ['icon' => 'calendar-alt', 'label' => 'Assigned Events', 'gradient' => 'purple'],
            'volunteer.tasks' => ['icon' => 'tasks', 'label' => 'My Tasks', 'gradient' => 'blue'],
            'volunteer.team' => ['icon' => 'user-friends', 'label' => 'My Team', 'gradient' => 'cyan'],
            'volunteer.prayer' => ['icon' => 'praying-hands', 'label' => 'Prayer', 'gradient' => 'purple'],
            'volunteer.ai-helper' => ['icon' => 'robot', 'label' => 'AI Helper', 'gradient' => 'cyan'],
            'volunteer.communication' => ['icon' => 'comments', 'label' => 'Communication', 'gradient' => 'pink'],
            'volunteer.settings' => ['icon' => 'cog', 'label' => 'Settings', 'gradient' => 'orange'],
        ]
    ],
    'member-portal' => [
        'title' => 'Member Portal',
        'subtitle' => 'Your Church Dashboard',
        'role' => 'Church Member',
        'routes' => [
            'portal.index' => ['icon' => 'home', 'label' => 'Dashboard', 'gradient' => 'green'],
            'portal.profile' => ['icon' => 'user', 'label' => 'My Profile', 'gradient' => 'blue'],
            'events.index' => ['icon' => 'calendar-alt', 'label' => 'Events', 'gradient' => 'purple'],
            'portal.giving' => ['icon' => 'hand-holding-usd', 'label' => 'My Giving', 'gradient' => 'orange'],
            'chat.index' => ['icon' => 'comments', 'label' => 'Member Chat', 'gradient' => 'cyan'],
            'devotional.index' => ['icon' => 'book-open', 'label' => 'Daily Devotional', 'gradient' => 'purple'],
            'prayer-requests.index' => ['icon' => 'praying-hands', 'label' => 'Prayer Requests', 'gradient' => 'pink'],
            'ai.chat' => ['icon' => 'robot', 'label' => 'AI Chat', 'gradient' => 'cyan'],
            'notifications.index' => ['icon' => 'bell', 'label' => 'Notifications', 'gradient' => 'orange'],
        ]
    ],
];

echo "📋 Portal configurations loaded\n";
echo "📝 Portals to generate: " . count($portals) . "\n\n";

foreach ($portals as $name => $config) {
    echo "Processing: {$config['title']}\n";
}

echo "\n✨ Layouts will include:\n";
echo "   - Glass morphism effects\n";
echo "   - Animated backgrounds\n";
echo "   - Modern sidebar design\n";
echo "   - Professional animations\n";
echo "   - Consistent styling\n\n";

echo "✅ Configuration complete!\n";
echo "📝 Use Cascade to generate the updated layout files.\n";
?>
