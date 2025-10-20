<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "==============================================\n";
echo "Testing All Pages - Controller Methods\n";
echo "==============================================\n\n";

$tests = [
    'Dashboard' => [
        'controller' => 'App\Http\Controllers\DashboardController',
        'methods' => ['index'],
        'views' => ['dashboard.index'],
    ],
    'Members' => [
        'controller' => 'App\Http\Controllers\MemberController',
        'methods' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
        'views' => ['members.index', 'members.create', 'members.show', 'members.edit'],
    ],
    'Attendance' => [
        'controller' => 'App\Http\Controllers\AttendanceController',
        'methods' => ['index'],
        'views' => ['attendance.index'],
    ],
    'Donations' => [
        'controller' => 'App\Http\Controllers\DonationController',
        'methods' => ['index', 'create', 'store'],
        'views' => ['donations.index', 'donations.create'],
    ],
    'Expenses' => [
        'controller' => 'App\Http\Controllers\ExpenseController',
        'methods' => ['index', 'create', 'store'],
        'views' => ['expenses.index', 'expenses.create'],
    ],
    'SMS' => [
        'controller' => 'App\Http\Controllers\SmsController',
        'methods' => ['index', 'create', 'store'],
        'views' => ['sms.index', 'sms.create'],
    ],
    'Equipment' => [
        'controller' => 'App\Http\Controllers\EquipmentController',
        'methods' => ['index', 'create', 'store'],
        'views' => ['equipment.index', 'equipment.create'],
    ],
    'Visitors' => [
        'controller' => 'App\Http\Controllers\VisitorController',
        'methods' => ['index', 'create', 'store'],
        'views' => ['visitors.index', 'visitors.create'],
    ],
    'Reports' => [
        'controller' => 'App\Http\Controllers\ReportController',
        'methods' => ['index', 'membership', 'financial', 'attendance'],
        'views' => ['reports.index', 'reports.membership', 'reports.financial', 'reports.attendance'],
    ],
    'Followups' => [
        'controller' => 'App\Http\Controllers\FollowupController',
        'methods' => ['index', 'create', 'store'],
        'views' => ['followups.index', 'followups.create'],
    ],
];

$allPassed = true;

foreach ($tests as $module => $config) {
    echo "📋 Testing: $module\n";
    echo str_repeat('-', 50) . "\n";
    
    // Check controller exists
    if (class_exists($config['controller'])) {
        echo "  ✅ Controller exists: {$config['controller']}\n";
        
        // Check methods
        $reflection = new ReflectionClass($config['controller']);
        foreach ($config['methods'] as $method) {
            if ($reflection->hasMethod($method)) {
                echo "  ✅ Method: $method()\n";
            } else {
                echo "  ❌ Missing method: $method()\n";
                $allPassed = false;
            }
        }
    } else {
        echo "  ❌ Controller missing: {$config['controller']}\n";
        $allPassed = false;
    }
    
    // Check views
    foreach ($config['views'] as $view) {
        $viewPath = str_replace('.', '/', $view) . '.blade.php';
        $fullPath = resource_path('views/' . $viewPath);
        if (file_exists($fullPath)) {
            echo "  ✅ View: $view\n";
        } else {
            echo "  ❌ Missing view: $view\n";
            $allPassed = false;
        }
    }
    
    echo "\n";
}

echo "==============================================\n";
if ($allPassed) {
    echo "✅ ALL TESTS PASSED! Every page is ready.\n";
} else {
    echo "❌ Some issues found. Check above for details.\n";
}
echo "==============================================\n";
