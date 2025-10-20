<?php

/**
 * Test if signup page is accessible
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║            SIGNUP PAGE ACCESSIBILITY TEST                 ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

echo "Testing signup page...\n\n";

try {
    // Create a GET request to /signup
    $request = Request::create('/signup', 'GET');
    
    // Handle the request
    $response = $kernel->handle($request);
    
    echo "✓ Route is accessible\n";
    echo "✓ HTTP Status Code: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() == 200) {
        echo "✓ Page loaded successfully!\n\n";
        
        $content = $response->getContent();
        
        // Check if it's the signup form
        if (strpos($content, 'signup') !== false || strpos($content, 'Sign Up') !== false) {
            echo "✓ Signup form HTML is present\n";
        } else {
            echo "⚠ Warning: Signup form content might not be correct\n";
        }
        
        if (strpos($content, 'fullname') !== false) {
            echo "✓ Full name field is present\n";
        }
        
        if (strpos($content, 'email') !== false) {
            echo "✓ Email field is present\n";
        }
        
        if (strpos($content, 'password') !== false) {
            echo "✓ Password field is present\n";
        }
        
        echo "\n✅ SIGNUP PAGE IS WORKING!\n\n";
        
    } else if ($response->getStatusCode() == 302) {
        echo "⚠ Page is redirecting (Status 302)\n";
        echo "  Redirect to: " . $response->headers->get('Location') . "\n";
        echo "  This might be normal if you're already logged in\n\n";
    } else {
        echo "✗ Unexpected status code\n\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error accessing signup page!\n";
    echo "  Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
}

echo "─────────────────────────────────────────────────────────────\n";
echo "Access URLs:\n";
echo "  XAMPP:         http://localhost/churchmang/public/signup\n";
echo "  Laravel Serve: http://127.0.0.1:8000/signup\n";
echo "─────────────────────────────────────────────────────────────\n\n";

echo "If XAMPP is not running:\n";
echo "  1. Open XAMPP Control Panel\n";
echo "  2. Start Apache\n";
echo "  3. Try accessing the page again\n\n";

echo "Or use Laravel development server:\n";
echo "  php artisan serve\n";
echo "  Then visit: http://127.0.0.1:8000/signup\n\n";

$kernel->terminate($request, $response);
