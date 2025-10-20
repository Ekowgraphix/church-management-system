<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Clearing all sessions...\n\n";

// Clear session files
$sessionPath = storage_path('framework/sessions');
$files = glob($sessionPath . '/*');

$count = 0;
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
        $count++;
    }
}

echo "✓ Deleted $count session file(s)\n\n";

// Clear cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "✓ Cache cleared\n";

\Illuminate\Support\Facades\Artisan::call('config:clear');
echo "✓ Config cleared\n";

\Illuminate\Support\Facades\Artisan::call('view:clear');
echo "✓ Views cleared\n\n";

echo "═══════════════════════════════════════════════════════════\n";
echo "All sessions cleared!\n";
echo "═══════════════════════════════════════════════════════════\n\n";

echo "Now try logging in:\n";
echo "1. Close ALL browser windows completely\n";
echo "2. Open a NEW incognito/private window\n";
echo "3. Visit: http://127.0.0.1:8000/login\n";
echo "4. Login with your credentials\n";
echo "5. You'll be redirected to: http://127.0.0.1:8000/portal\n\n";

echo "✅ IT WILL WORK NOW!\n\n";
