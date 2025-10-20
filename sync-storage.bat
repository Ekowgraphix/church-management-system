@echo off
echo ========================================
echo   Storage Sync Utility
echo   Syncing storage files to public...
echo ========================================
echo.

xcopy storage\app\public public\storage /E /I /Y /Q

echo.
echo ========================================
echo   Sync Complete!
echo   All storage files copied to public
echo ========================================
echo.
pause
