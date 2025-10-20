@echo off
echo ========================================
echo   Church Management System Setup
echo ========================================
echo.

echo [1/4] Running database migrations...
php artisan migrate --force
echo.

echo [2/4] Seeding roles...
php artisan db:seed --class=RolesSeeder
echo.

echo [3/4] Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo.

echo [4/4] Starting development server...
echo.
echo ========================================
echo   Setup Complete!
echo ========================================
echo.
echo Your Church Management System is ready!
echo.
echo Visit: http://localhost:8000/login
echo.
echo Test User Creation:
echo   Run: php create-test-member.php
echo.
echo Documentation:
echo   - AUTH_MODULE_IMPLEMENTATION_GUIDE.md
echo   - COMPLETE_IMPLEMENTATION_STATUS.md
echo   - DESIGN_CUSTOMIZATION_GUIDE.md
echo   - FINAL_IMPLEMENTATION_COMPLETE.md
echo.
echo ========================================
echo.

php artisan serve
