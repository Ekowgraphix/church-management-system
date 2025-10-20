@echo off
echo ==========================================
echo SETUP GITHUB FOR CHURCH MANAGEMENT SYSTEM
echo ==========================================
echo.

REM Check if Git is installed
git --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Git is not installed!
    echo.
    echo Please install Git first:
    echo 1. Go to: https://git-scm.com/download/win
    echo 2. Download and install Git for Windows
    echo 3. Restart this script after installation
    echo.
    pause
    exit /b 1
)

echo Git is installed!
echo.

REM Check if already initialized
if exist .git (
    echo Git repository already initialized.
    echo.
    git status
    echo.
    echo To push changes:
    echo   git add .
    echo   git commit -m "Your commit message"
    echo   git push
    echo.
    pause
    exit /b 0
)

echo Initializing Git repository...
git init
echo.

echo Creating initial commit...
git add .
git commit -m "Initial commit: Church Management System"
echo.

echo ==========================================
echo NEXT STEPS:
echo ==========================================
echo.
echo 1. Go to GitHub: https://github.com
echo 2. Click '+' (top right) and 'New repository'
echo 3. Name: church-management-system
echo 4. Choose Private (recommended)
echo 5. DO NOT check 'Initialize with README'
echo 6. Click 'Create repository'
echo.
echo 7. Copy and run these commands:
echo.
echo    git remote add origin https://github.com/YOUR-USERNAME/church-management-system.git
echo    git branch -M main
echo    git push -u origin main
echo.
echo Replace YOUR-USERNAME with your GitHub username
echo.
echo ==========================================
pause
