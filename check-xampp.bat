@echo off
echo ============================================
echo XAMPP Diagnostic Tool
echo ============================================
echo.

echo Checking Port 80 (Apache)...
netstat -ano | findstr ":80 " | findstr "LISTENING"
echo.

echo Checking Port 443 (Apache SSL)...
netstat -ano | findstr ":443 " | findstr "LISTENING"
echo.

echo Checking Port 3306 (MySQL)...
netstat -ano | findstr ":3306" | findstr "LISTENING"
echo.

echo Checking if World Wide Web Publishing Service is running...
sc query w3svc
echo.

echo Checking if SQL Server is running...
sc query MSSQLSERVER
echo.

echo ============================================
echo Common Solutions:
echo ============================================
echo 1. If port 80 is blocked by IIS/W3SVC:
echo    - Run: net stop w3svc
echo    - Or disable IIS in Windows Features
echo.
echo 2. If port 80 is blocked by another app:
echo    - Check the PID in the output above
echo    - Run: tasklist /FI "PID eq [PID_NUMBER]"
echo.
echo 3. If port 3306 is blocked by MySQL service:
echo    - Run: net stop MySQL80
echo    - Or stop MySQL service from Services
echo.
echo 4. Try running XAMPP Control Panel as Administrator
echo.
pause
