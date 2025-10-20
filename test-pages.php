<?php

echo "==============================================\n";
echo "Church Management System - Page Test\n";
echo "==============================================\n\n";

$baseUrl = 'http://127.0.0.1:8000';
$pages = [
    'Dashboard' => '/dashboard',
    'Members Index' => '/members',
    'Members Create' => '/members/create',
    'Attendance' => '/attendance',
    'Donations' => '/donations',
    'Donations Create' => '/donations/create',
    'Expenses' => '/expenses',
    'Expenses Create' => '/expenses/create',
    'SMS' => '/sms',
    'SMS Create' => '/sms/create',
    'Equipment' => '/equipment',
    'Visitors' => '/visitors',
    'Reports' => '/reports',
    'Followups' => '/followups',
];

echo "Testing pages (requires login)...\n\n";

foreach ($pages as $name => $path) {
    $url = $baseUrl . $path;
    echo str_pad($name, 25) . " => " . $url . "\n";
}

echo "\n==============================================\n";
echo "To test manually:\n";
echo "1. Login at: $baseUrl/login\n";
echo "2. Use: admin@church.com / password\n";
echo "3. Visit each page above\n";
echo "==============================================\n";
