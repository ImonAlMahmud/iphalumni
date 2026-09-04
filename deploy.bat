@echo off
cd /d "%~dp0"

set DEPLOY_BRANCH=main
set DEPLOY_SECRET=iph_alumni_secret_key_deploy_2026
set DEPLOY_DOMAIN=iphalumni.dev.cv
set DEPLOY_URL=https://iphalumni.dev.cv/deploy.php

:: CLI Quick Actions
if "%~1"=="--webhook-only" goto :TRIGGER_WEBHOOK_DIRECT
if "%~1"=="--clear-cache" goto :DO_CLEAR_CACHE
if not "%~1"=="" (
    set COMMIT_MSG=%~1
    goto :EXECUTE_AUTO_DEPLOY
)

title IPH Alumni - Auto Deploy & Sync System 2>nul
color 0B 2>nul

:MAIN_MENU
cls
echo ===============================================================================
echo                IPH ALUMNI ASSOCIATION - AUTO DEPLOY SYSTEM                     
echo ===============================================================================
echo   Live Server URL   : https://%DEPLOY_DOMAIN%
echo   Deployment Branch : %DEPLOY_BRANCH%
echo   Webhook Endpoint  : %DEPLOY_URL%
echo ===============================================================================
echo.
echo   [1] Full Auto-Deploy (Custom Commit Message + Git Push + Live Webhook)
echo   [2] Quick Auto-Deploy (Auto-Timestamp Commit + Git Push + Live Webhook)
echo   [3] Trigger Live Webhook Only (Re-run Migrations & Cache on Server)
echo   [4] Clear Local Laravel Cache (php artisan optimize:clear)
echo   [5] Check Local Git Status & Remote URL
echo   [6] Exit
echo.
echo ===============================================================================
set CHOICE=
set /p CHOICE="Select an option [1-6] and press Enter: "

if "%CHOICE%"=="1" goto :CUSTOM_COMMIT
if "%CHOICE%"=="2" goto :QUICK_COMMIT
if "%CHOICE%"=="3" goto :TRIGGER_WEBHOOK_MENU
if "%CHOICE%"=="4" goto :DO_CLEAR_CACHE_MENU
if "%CHOICE%"=="5" goto :DO_GIT_STATUS
if "%CHOICE%"=="6" goto :DO_EXIT

echo [!] Invalid option.
timeout /t 2 >nul
goto :MAIN_MENU

:CUSTOM_COMMIT
cls
echo ===============================================================================
echo                     STEP 1: CUSTOM COMMIT MESSAGE                              
echo ===============================================================================
echo.
set COMMIT_MSG=
set /p COMMIT_MSG="Enter your commit message (or press Enter for default): "
if "%COMMIT_MSG%"=="" set COMMIT_MSG=Auto deploy update on %DATE% at %TIME%
goto :EXECUTE_AUTO_DEPLOY

:QUICK_COMMIT
set COMMIT_MSG=Quick deploy update on %DATE% at %TIME%
goto :EXECUTE_AUTO_DEPLOY

:EXECUTE_AUTO_DEPLOY
cls
echo ===============================================================================
echo                       STEP 1: LOCAL GIT SYNC & PUSH                            
echo ===============================================================================
echo.

if not exist ".git" (
    echo [INFO] Git repository not initialized. Initializing now...
    git init -b %DEPLOY_BRANCH%
    echo.
    set /p REPO_URL="Enter your remote Git URL (e.g. https://github.com/user/repo.git): "
    if not "%REPO_URL%"=="" git remote add origin %REPO_URL%
)

echo [1/3] Staging changes...
git add -A

echo.
echo [2/3] Committing changes...
git commit -m "%COMMIT_MSG%"

echo.
echo [3/3] Pushing to remote branch (%DEPLOY_BRANCH%)...
git push origin %DEPLOY_BRANCH%
if %errorlevel% neq 0 (
    echo [WARNING] Default push failed. Trying with upstream set:
    git push -u origin %DEPLOY_BRANCH%
)

echo.
echo ===============================================================================
echo                    STEP 2: TRIGGERING LIVE WEBHOOK DEPLOY                      
echo ===============================================================================
echo.

:TRIGGER_WEBHOOK_DIRECT
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\deploy_webhook.ps1" -Url "%DEPLOY_URL%" -Secret "%DEPLOY_SECRET%" -Branch "%DEPLOY_BRANCH%"
if not "%~1"=="" goto :DO_EXIT
pause
goto :MAIN_MENU

:TRIGGER_WEBHOOK_MENU
cls
echo ===============================================================================
echo                    TRIGGERING LIVE WEBHOOK DEPLOYMENT                          
echo ===============================================================================
echo.
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\deploy_webhook.ps1" -Url "%DEPLOY_URL%" -Secret "%DEPLOY_SECRET%" -Branch "%DEPLOY_BRANCH%"
echo.
pause
goto :MAIN_MENU

:DO_CLEAR_CACHE
php artisan optimize:clear
goto :DO_EXIT

:DO_CLEAR_CACHE_MENU
cls
echo ===============================================================================
echo                      CLEARING LOCAL LARAVEL CACHES                             
echo ===============================================================================
echo.
php artisan optimize:clear
echo.
pause
goto :MAIN_MENU

:DO_GIT_STATUS
cls
echo ===============================================================================
echo                        GIT STATUS & CONFIGURATION                              
echo ===============================================================================
echo.
if exist ".git" (
    echo [Git Status]
    git status -s
    echo.
    echo [Remote URLs]
    git remote -v
    echo.
    echo [Current Branch]
    git branch --show-current
) else (
    echo [INFO] Git repository is not initialized.
)
echo.
echo Options:
echo   [1] Set/Change Git Remote Origin URL
echo   [2] Back to Main Menu
echo.
set GIT_OPT=
set /p GIT_OPT="Select an option [1-2]: "
if "%GIT_OPT%"=="1" (
    echo.
    set /p NEW_REMOTE="Enter Git Remote URL (e.g. https://github.com/user/repo.git): "
    if not "%NEW_REMOTE%"=="" (
        git remote remove origin 2>nul
        git remote add origin %NEW_REMOTE%
        echo Remote origin updated to: %NEW_REMOTE%
    )
    pause
)
goto :MAIN_MENU

:DO_EXIT
exit /b 0
