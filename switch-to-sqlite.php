<?php

echo "============================================\n";
echo "Switching to SQLite Database\n";
echo "============================================\n\n";

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "❌ .env file not found!\n";
    echo "Creating from .env.example...\n";
    copy(__DIR__ . '/.env.example', $envFile);
}

// Read .env file
$envContent = file_get_contents($envFile);

// Backup original
file_put_contents($envFile . '.backup', $envContent);
echo "✅ Backup created: .env.backup\n\n";

// Replace MySQL with SQLite
$envContent = preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=sqlite', $envContent);
$envContent = preg_replace('/^DB_HOST=.*/m', '# DB_HOST=127.0.0.1', $envContent);
$envContent = preg_replace('/^DB_PORT=.*/m', '# DB_PORT=3306', $envContent);
$envContent = preg_replace('/^DB_DATABASE=.*/m', '# DB_DATABASE=churchmang', $envContent);
$envContent = preg_replace('/^DB_USERNAME=.*/m', '# DB_USERNAME=root', $envContent);
$envContent = preg_replace('/^DB_PASSWORD=.*/m', '# DB_PASSWORD=', $envContent);

// Write updated .env
file_put_contents($envFile, $envContent);

echo "✅ .env updated to use SQLite\n\n";

echo "Database Configuration:\n";
echo "  DB_CONNECTION: sqlite\n";
echo "  Database File: database/database.sqlite\n\n";

echo "============================================\n";
echo "Next Steps:\n";
echo "============================================\n";
echo "1. Run migrations: php artisan migrate\n";
echo "2. Seed database: php artisan db:seed\n";
echo "3. Start server: php artisan serve\n";
echo "4. Access at: http://127.0.0.1:8000\n\n";

echo "To revert to MySQL, restore from: .env.backup\n";
