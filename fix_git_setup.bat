@echo off
echo ==========================================
echo FIXING GIT SETUP
echo ==========================================
echo.

REM Fix dubious ownership issue
echo Adding directory as safe...
git config --global --add safe.directory F:/xampp/htdocs/churchmang
echo.

REM Configure Git user
echo Configuring Git user...
git config --global user.name "Billy Beams"
git config --global user.email "ekowjeremy@gmail.com"
echo.

REM Check if already initialized
if exist .git (
    echo Git already initialized!
    echo.
    echo Making first commit...
    git add .
    git commit -m "Initial commit: Church Management System"
    echo.
) else (
    echo Initializing Git repository...
    git init
    echo.
    
    echo Creating initial commit...
    git add .
    git commit -m "Initial commit: Church Management System"
    echo.
)

echo ==========================================
echo SUCCESS! Git is ready!
echo ==========================================
echo.
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
