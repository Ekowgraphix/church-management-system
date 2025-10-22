@echo off
echo ==========================================
echo CREATING STORAGE SYMLINK (Run as Admin)
echo ==========================================
echo.

cd /d "%~dp0"

echo Removing old storage directory if exists...
if exist "public\storage" (
    rmdir /S /Q "public\storage"
    echo Old directory removed
)

echo.
echo Creating junction point...
mklink /J "public\storage" "storage\app\public"

echo.
echo ==========================================
echo DONE!
echo ==========================================
echo.
echo Now refresh your browser (Ctrl+F5)
echo Your images should now be visible!
echo.
pause
