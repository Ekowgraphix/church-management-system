@echo off
echo ==========================================
echo OPENING PHP.INI FILE FOR EDITING
echo ==========================================
echo.
echo This will open php.ini in Notepad.
echo.
echo FIND AND CHANGE THESE LINES:
echo.
echo   upload_max_filesize = 100M
echo   Change to: upload_max_filesize = 100M
echo.
echo   post_max_size = 100M  
echo   Change to: post_max_size = 100M
echo.
echo   max_execution_time = 300
echo   Change to: max_execution_time = 300
echo.
echo After saving, run: restart_apache.bat
echo.
echo Press any key to open php.ini...
pause >nul

notepad "F:\xampp\php\php.ini"
