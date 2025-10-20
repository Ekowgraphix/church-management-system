@echo off
echo ==========================================
echo RESTARTING APACHE FOR NEW UPLOAD LIMITS
echo ==========================================
echo.

echo Stopping Apache...
taskkill /F /IM httpd.exe 2>nul
timeout /t 3 >nul

echo Starting Apache...
start "" "F:\xampp\apache\bin\httpd.exe"
timeout /t 3 >nul

echo.
echo ==========================================
echo DONE! Apache restarted.
echo You can now upload files up to 100MB!
echo ==========================================
echo.
echo Press any key to close this window...

pause >nul
