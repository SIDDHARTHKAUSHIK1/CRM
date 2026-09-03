#!/usr/bin/env bash
# ============================================================================
# Quick Redeploy Script for CRM
# Pulls latest code, migrates, rebuilds assets, caches, and restarts workers.
# ============================================================================

set -e

echo -e "\n\033[1;36m==> 1. Navigating to project directory...\033[0m"
cd /var/www/crm

echo -e "\n\033[1;36m==> 2. Pulling latest code from GitHub...\033[0m"
git pull origin main

echo -e "\n\033[1;36m==> 3. Updating PHP dependencies...\033[0m"
composer install --no-dev --optimize-autoloader --no-interaction

echo -e "\n\033[1;36m==> 4. Running database migrations & storage link...\033[0m"
php artisan migrate --force
php artisan storage:link 2>/dev/null || true

# Ensure APP_URL in .env points to the production domain instead of localhost
if grep -qE "^APP_URL=.*localhost" .env 2>/dev/null; then
    sed -i "s|^APP_URL=.*|APP_URL=https://realestate.aflix.co.in|" .env
fi

echo -e "\n\033[1;36m==> 5. Building root frontend assets...\033[0m"
npm run build

echo -e "\n\033[1;36m==> 6. Building Admin panel assets...\033[0m"
(cd packages/Crm/Admin && npm run build)

echo -e "\n\033[1;36m==> 7. Fixing storage and cache permissions...\033[0m"
chown -R www-data:www-data storage bootstrap/cache public

echo -e "\n\033[1;36m==> 8. Clearing and recaching application config, routes, and views...\033[0m"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "\n\033[1;36m==> 9. Restarting queue worker & WhatsApp gateway...\033[0m"
systemctl restart crm-queue-worker whatsapp-gateway

echo -e "\n\033[1;32m========================================================\033[0m"
echo -e "\033[1;32m✔ Project updated successfully! Changes are now LIVE.\033[0m"
echo -e "\033[1;32m========================================================\033[0m"
