@echo off
cd /d "%~dp0"

set DEPLOY_SECRET=iph_alumni_secret_key_deploy_2026
set DEPLOY_DOMAIN=iphalumni.dev.cv
set DEPLOY_URL=https://%DEPLOY_DOMAIN%/deploy.php

:: CLI Quick Actions
if "%~1"=="--direct" goto :DO_DIRECT_SYNC
if "%~1"=="--full" goto :DO_FULL_SYNC
if "%~1"=="--webhook-only" goto :TRIGGER_WEBHOOK_DIRECT
if "%~1"=="--clear-cache" goto :DO_CLEAR_CACHE

title IPH Alumni - Local to Server Auto Deploy 2>nul
color 0B 2>nul

:MAIN_MENU
cls
echo ===============================================================================
echo            IPH ALUMNI - DIRECT LOCAL TO SERVER AUTO DEPLOY                     
echo                       (NO GITHUB REQUIRED)                                     
echo ===============================================================================
echo   Live Server URL  : https://%DEPLOY_DOMAIN%
echo   Deploy Endpoint  : %DEPLOY_URL%
echo ===============================================================================
echo.
echo   [1] Direct Auto-Deploy (Sync App, Config, Database, Views to Server)
echo   [2] Full Auto-Deploy (Sync Everything including Vendor Folder)
echo   [3] Trigger Server Cache Clear & Migrations (No file upload)
echo   [4] Clear Local Laravel Cache (php artisan optimize:clear)
echo   [5] Exit
echo.
echo ===============================================================================
set CHOICE=
set /p CHOICE="Select an option [1-5] and press Enter: "

if "%CHOICE%"=="1" goto :DO_DIRECT_SYNC
if "%CHOICE%"=="2" goto :DO_FULL_SYNC
if "%CHOICE%"=="3" goto :TRIGGER_WEBHOOK_MENU
if "%CHOICE%"=="4" goto :DO_CLEAR_CACHE_MENU
if "%CHOICE%"=="5" goto :DO_EXIT

echo [!] Invalid option.
timeout /t 2 >nul
goto :MAIN_MENU

:DO_DIRECT_SYNC
cls
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\local_direct_deploy.ps1" -Url "%DEPLOY_URL%" -Secret "%DEPLOY_SECRET%"
echo.
pause
goto :MAIN_MENU

:DO_FULL_SYNC
cls
echo [INFO] Packaging including Vendor folder may take a minute...
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\local_direct_deploy.ps1" -Url "%DEPLOY_URL%" -Secret "%DEPLOY_SECRET%" -IncludeVendor
echo.
pause
goto :MAIN_MENU

:TRIGGER_WEBHOOK_DIRECT
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\deploy_webhook.ps1" -Url "%DEPLOY_URL%" -Secret "%DEPLOY_SECRET%" -Branch "main"
if not "%~1"=="" goto :DO_EXIT
pause
goto :MAIN_MENU

:TRIGGER_WEBHOOK_MENU
cls
echo ===============================================================================
echo                    TRIGGERING SERVER CACHE & MIGRATIONS                        
echo ===============================================================================
echo.
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\deploy_webhook.ps1" -Url "%DEPLOY_URL%" -Secret "%DEPLOY_SECRET%" -Branch "main"
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

:DO_EXIT
exit /b 0
