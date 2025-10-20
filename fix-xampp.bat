@echo off
echo ============================================
echo XAMPP Startup Fix Script
echo ============================================
echo.
echo This script will attempt to fix common XAMPP startup issues.
echo Please run this as Administrator!
echo.
pause

echo.
echo [1/6] Stopping any conflicting services...
net stop w3svc 2>nul
net stop MySQL80 2>nul
net stop MySQL 2>nul
echo Done.

echo.
echo [2/6] Checking Visual C++ Redistributables...
echo If Apache fails, you may need to install:
echo - Visual C++ Redistributable 2015-2022 (x64)
echo Download from: https://aka.ms/vs/17/release/vc_redist.x64.exe
echo.

echo.
echo [3/6] Resetting Apache configuration...
cd /d F:\xampp\apache\bin
httpd.exe -t
echo.

echo.
echo [4/6] Checking MySQL data directory...
if not exist "F:\xampp\mysql\data\mysql" (
    echo WARNING: MySQL data directory is missing!
    echo You may need to initialize MySQL.
    echo Run: F:\xampp\mysql\bin\mysqld.exe --initialize-insecure --console
) else (
    echo MySQL data directory exists.
)
echo.

echo.
echo [5/6] Starting Apache...
cd /d F:\xampp
xampp_start.exe
echo.

echo.
echo [6/6] Checking if services are running...
timeout /t 5 /nobreak >nul
netstat -ano | findstr ":80 " | findstr "LISTENING"
netstat -ano | findstr ":3306" | findstr "LISTENING"
echo.

echo ============================================
echo If services still won't start:
echo ============================================
echo 1. Open XAMPP Control Panel AS ADMINISTRATOR
echo 2. Check the logs:
echo    - Apache: F:\xampp\apache\logs\error.log
echo    - MySQL: F:\xampp\mysql\data\*.err
echo.
echo 3. Common fixes:
echo    - Install missing Visual C++ Redistributables
echo    - Check Windows Firewall/Antivirus
echo    - Repair XAMPP installation
echo.
pause
