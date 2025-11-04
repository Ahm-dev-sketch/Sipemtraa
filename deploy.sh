#!/usr/bin/env bash
# ============================================
# Laravel Production Deployment Script (Linux)
# Usage: sudo ./deploy.sh  OR run as user with sudo privileges
# ============================================

set -euo pipefail
IFS=$'\n\t'

echo "========================================"
echo " Laravel Production Optimization (Linux)"
echo "========================================"
echo

# Check for required commands
for cmd in php composer npm; do
  if ! command -v "$cmd" >/dev/null 2>&1; then
    echo "ERROR: '$cmd' is not installed or not in PATH. Aborting."
    exit 1
  fi
done

# Optional: use a specific user for file permissions (common: www-data)
WEB_USER=${WEB_USER:-www-data}
WEB_GROUP=${WEB_GROUP:-www-data}

# 1. Clear caches
echo "[1/8] Clearing all caches..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true
echo "Done!"
echo

# 2. Caching configuration
echo "[2/8] Caching configuration..."
php artisan config:cache
echo "Done!"
echo

# 3. Caching routes
echo "[3/8] Caching routes..."
php artisan route:cache
echo "Done!"
echo

# 4. Caching views
echo "[4/8] Caching views..."
php artisan view:cache
echo "Done!"
echo

# 5. Caching events
echo "[5/8] Caching events..."
php artisan event:cache || true
echo "Done!"
echo

# 6. Composer install (production)
echo "[6/8] Installing Composer dependencies (no-dev)..."
composer install --no-dev --optimize-autoloader --prefer-dist
echo "Done!"
echo

# 7. Build frontend assets
echo "[7/8] Building frontend assets..."
# Prefer npm ci in CI environments when package-lock.json is present
if [ -f package-lock.json ]; then
  npm ci --production --silent
else
  npm install --production --silent
fi
npm run build
echo "Done!"
echo

# 8. Set proper permissions
echo "[8/8] Setting proper permissions..."
# Use sudo for chown/chmod if not root
if [ "$(id -u)" -ne 0 ]; then
  SUDO=sudo
else
  SUDO=
fi
$SUDO chown -R "$WEB_USER":"$WEB_GROUP" storage bootstrap/cache || true
$SUDO chmod -R 775 storage bootstrap/cache || true
# Optional: give group write to vendor if needed (uncommon)
# $SUDO chmod -R g+w vendor

echo "Done!"
echo

echo "========================================"
echo " Optimization Complete!"
echo "========================================"
echo
echo "Notes:"
echo " - Ensure your .env is configured on the server (do NOT commit .env to git)."
echo " - This script is intended for Linux hosts; adjust WEB_USER/WEB_GROUP if your web server uses a different user."

echo
exit 0
