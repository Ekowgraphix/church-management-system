<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use App\Models\User;

echo "Testing portal access...\n\n";

try {
    // Get the user
    $user = User::where('email', '98billybeams@beamers.com')->first();
    
    if (!$user) {
        echo "User not found!\n";
        exit(1);
    }
    
    echo "User: {$user->name}\n";
    echo "Email verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
    echo "Role: " . ($user->roles->first()->name ?? 'No role') . "\n\n";
    
    // Create authenticated request
    $request = Request::create('/portal', 'GET');
    
    // Simulate authentication
    auth()->login($user);
    
    echo "Attempting to access /portal...\n";
    
    // Handle the request
    $response = $kernel->handle($request);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() == 200) {
        echo "✓ Portal page loaded successfully!\n";
    } else if ($response->getStatusCode() == 302) {
        echo "⚠ Redirecting to: " . $response->headers->get('Location') . "\n";
    } else {
        echo "✗ Error accessing portal\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\nDirect URL: http://127.0.0.1:8000/portal\n";
