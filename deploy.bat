@echo off
REM ============================================
REM Laravel Production Deployment Script
REM Run this script when deploying to production
REM ============================================

echo ========================================
echo  Laravel Production Optimization
echo ========================================
echo.

REM Clear all caches first
echo [1/8] Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
echo Done!
echo.

REM Optimize for production
echo [2/8] Caching configuration...
php artisan config:cache
echo Done!
echo.

echo [3/8] Caching routes...
php artisan route:cache
echo Done!
echo.

echo [4/8] Caching views...
php artisan view:cache
echo Done!
echo.

echo [5/8] Caching events...
php artisan event:cache
echo Done!
echo.

REM Optimize Composer autoload
echo [6/8] Optimizing Composer autoload...
composer install --optimize-autoloader --no-dev
echo Done!
echo.

REM Build frontend assets
echo [7/8] Building frontend assets...
call npm run build
echo Done!
echo.

REM Set proper permissions
echo [8/8] Setting proper permissions...
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
echo Done!
echo.

echo ========================================
echo  Optimization Complete!
echo ========================================
echo.
echo Your Laravel application is now optimized for production!
echo.
pause
