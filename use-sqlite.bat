@echo off
echo ============================================
echo Switch to SQLite (Quick Development Setup)
echo ============================================
echo.
echo This will configure your app to use SQLite
echo instead of MySQL for quick testing.
echo.
pause

echo.
echo [1/3] Creating SQLite database file...
cd /d F:\xampp\htdocs\churchmang
if not exist "database\database.sqlite" (
    type nul > database\database.sqlite
    echo SQLite database created.
) else (
    echo SQLite database already exists.
)

echo.
echo [2/3] Updating .env file...
echo Please manually edit .env and change:
echo.
echo FROM:
echo   DB_CONNECTION=mysql
echo   DB_HOST=127.0.0.1
echo   DB_PORT=3306
echo   DB_DATABASE=churchmang
echo   DB_USERNAME=root
echo   DB_PASSWORD=
echo.
echo TO:
echo   DB_CONNECTION=sqlite
echo   # DB_HOST=127.0.0.1
echo   # DB_PORT=3306
echo   # DB_DATABASE=churchmang
echo   # DB_USERNAME=root
echo   # DB_PASSWORD=
echo.
pause

echo.
echo [3/3] Running migrations...
php artisan migrate
echo.

echo.
echo ============================================
echo Setup Complete!
echo ============================================
echo.
echo You can now use the application with SQLite.
echo To start the server, run:
echo   php artisan serve
echo.
echo Access at: http://127.0.0.1:8000
echo.
pause
