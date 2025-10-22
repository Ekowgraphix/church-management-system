@echo off
echo ==========================================
echo STARTING CHURCH MANAGEMENT SYSTEM
echo Network Access Enabled
echo ==========================================
echo.
echo Your IP Address: 192.168.249.253
echo Port: 8000
echo.
echo Access from this computer:
echo   http://localhost:8000
echo   http://127.0.0.1:8000
echo   http://192.168.249.253:8000
echo.
echo Access from other devices (phones, tablets, computers):
echo   http://192.168.249.253:8000
echo.
echo Make sure all devices are on the same Wi-Fi network!
echo.
echo ==========================================
echo Starting server...
echo ==========================================
echo.

php artisan serve --host=0.0.0.0 --port=8000

echo.
echo Server stopped.
pause
