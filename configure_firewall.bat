@echo off
echo ==========================================
echo CONFIGURE WINDOWS FIREWALL
echo For Church Management System Network Access
echo ==========================================
echo.
echo This script will allow PHP to accept connections
echo from other devices on your network.
echo.
echo You need to run this as ADMINISTRATOR!
echo.
echo Right-click this file and select "Run as administrator"
echo.
pause
echo.
echo Adding firewall rule...
echo.

netsh advfirewall firewall add rule name="Church Management System - Laravel Server" dir=in action=allow protocol=TCP localport=8000

echo.
echo Adding PHP to allowed apps...
echo.

netsh advfirewall firewall add rule name="Church Management System - PHP" dir=in action=allow program="F:\xampp\php\php.exe" enable=yes

echo.
echo ==========================================
echo FIREWALL CONFIGURATION COMPLETE!
echo ==========================================
echo.
echo Your system is now accessible from other devices!
echo.
echo Next steps:
echo 1. Start the server: run start_server_network.bat
echo 2. Access from other devices: http://192.168.249.253:8000
echo.
echo ==========================================
pause
