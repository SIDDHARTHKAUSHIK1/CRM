#!/usr/bin/env bash
# ============================================================================
# One-shot deployment script — WhatsApp CRM -> realestate.aflix.co.in
#
# Safe to re-run: every step checks before it acts, so running this twice
# won't break anything or duplicate config. The one thing that DOES change
# on every re-run is the database password (it's randomly regenerated and
# immediately re-applied to both MySQL and .env in the same run, so nothing
# gets out of sync — just don't be surprised the value in the secrets file
# is different after a second run).
#
# Usage on the VPS:
#   nano deploy.sh        # paste this whole file, save (Ctrl+O, Enter, Ctrl+X)
#   chmod +x deploy.sh
#   sudo ./deploy.sh
#
# The only thing this script cannot do for you: linking WhatsApp itself
# needs a real phone to scan a QR code. Everything else is unattended.
# ============================================================================

set -euo pipefail

# ---------------------------- CONFIG ----------------------------
DOMAIN="realestate.aflix.co.in"
REPO_URL="https://github.com/SIDDHARTHKAUSHIK1/CRM.git"
DEPLOY_PATH="/var/www/crm"
CERTBOT_EMAIL="sidd2004.sk@gmail.com"
PHP_VERSION="8.3"
DB_NAME="crm_production"
DB_USER="crm_user"
WHATSAPP_DEFAULT_COUNTRY_CODE="91"
WHATSAPP_MAX_MEDIA_MB="100"
SECRETS_FILE="/root/crm-deploy-secrets.txt"
# ------------------------------------------------------------------

if [[ $EUID -ne 0 ]]; then
  echo "Please run this with sudo: sudo ./deploy.sh"
  exit 1
fi

step() { echo -e "\n\033[1;36m==> $1\033[0m"; }

set_env() {
  local key="$1" value="$2" file="$3"
  if grep -qE "^${key}=" "$file" 2>/dev/null; then
    sed -i "s|^${key}=.*|${key}=${value}|" "$file"
  else
    echo "${key}=${value}" >> "$file"
  fi
}

step "0. Checking DNS points at this server"
SERVER_IP=$(curl -s -4 https://ifconfig.me || true)
DOMAIN_IP=$(dig +short "$DOMAIN" 2>/dev/null | tail -n1 || true)
if [[ -n "$SERVER_IP" && -n "$DOMAIN_IP" && "$SERVER_IP" != "$DOMAIN_IP" ]]; then
  echo "WARNING: $DOMAIN currently resolves to '$DOMAIN_IP', this server's IP is '$SERVER_IP'."
  echo "SSL setup (step 10) will fail until DNS is corrected — the script will keep going and"
  echo "tell you the exact command to re-run for just that step once DNS has propagated."
fi

step "1. Installing/checking required packages"
export DEBIAN_FRONTEND=noninteractive
apt-get update -y

if ! php -v 2>/dev/null | grep -q "PHP ${PHP_VERSION}"; then
  apt-get install -y software-properties-common
  add-apt-repository -y ppa:ondrej/php
  apt-get update -y
fi
apt-get install -y php${PHP_VERSION}-fpm php${PHP_VERSION}-cli php${PHP_VERSION}-mysql \
  php${PHP_VERSION}-mbstring php${PHP_VERSION}-xml php${PHP_VERSION}-bcmath \
  php${PHP_VERSION}-curl php${PHP_VERSION}-zip php${PHP_VERSION}-gd php${PHP_VERSION}-intl

step "1b. Raising PHP upload limits (Ubuntu's default 2M/8M is too small for video brochures)"
for INI in /etc/php/${PHP_VERSION}/fpm/php.ini /etc/php/${PHP_VERSION}/cli/php.ini; do
  if [[ -f "$INI" ]]; then
    sed -i "s/^upload_max_filesize = .*/upload_max_filesize = 110M/" "$INI"
    sed -i "s/^post_max_size = .*/post_max_size = 120M/" "$INI"
  fi
done
systemctl restart php${PHP_VERSION}-fpm

command -v composer >/dev/null 2>&1 || {
  curl -sS https://getcomposer.org/installer | php
  mv composer.phar /usr/local/bin/composer
}

command -v nginx >/dev/null 2>&1 || apt-get install -y nginx
command -v dig >/dev/null 2>&1 || apt-get install -y dnsutils
(command -v mysql >/dev/null 2>&1 || command -v mariadb >/dev/null 2>&1) || apt-get install -y mysql-server
command -v certbot >/dev/null 2>&1 || apt-get install -y certbot python3-certbot-nginx
apt-get install -y git unzip

if ! command -v node >/dev/null 2>&1; then
  echo "Node.js not found — installing Node 20 LTS via NodeSource"
  curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
  apt-get install -y nodejs
fi
NODE_MAJOR=$(node -v | sed 's/v//' | cut -d. -f1)
if [[ "$NODE_MAJOR" -lt 18 ]]; then
  echo "WARNING: Node $(node -v) is older than recommended (18+) for the WhatsApp gateway."
fi

step "2. Creating the database (does not touch any existing database on this server)"
DB_PASS=$(openssl rand -hex 20)
mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost'; FLUSH PRIVILEGES;"

step "3. Getting the code"
mkdir -p "$DEPLOY_PATH"
if [[ -d "$DEPLOY_PATH/.git" ]]; then
  echo "Existing checkout found — pulling latest instead of cloning"
  git -C "$DEPLOY_PATH" pull
else
  GIT_TERMINAL_PROMPT=0 git clone "$REPO_URL" "$DEPLOY_PATH" || {
    echo "ERROR: git clone failed — this almost always means the repo is private."
    echo "Fix: either make it temporarily public, or edit REPO_URL at the top of this"
    echo "script to embed a token, e.g.:"
    echo "  https://<github_username>:<personal_access_token>@github.com/SIDDHARTHKAUSHIK1/CRM.git"
    echo "then re-run this script."
    exit 1
  }
fi
cd "$DEPLOY_PATH"

step "4. Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

step "5. Writing production .env"
[[ -f .env ]] || cp .env.example .env
WHATSAPP_GATEWAY_KEY=$(openssl rand -hex 32)

set_env "APP_ENV" "production" .env
set_env "APP_DEBUG" "false" .env
set_env "APP_URL" "https://${DOMAIN}" .env
set_env "DB_CONNECTION" "mysql" .env
set_env "DB_HOST" "127.0.0.1" .env
set_env "DB_PORT" "3306" .env
set_env "DB_DATABASE" "${DB_NAME}" .env
set_env "DB_USERNAME" "${DB_USER}" .env
set_env "DB_PASSWORD" "${DB_PASS}" .env
set_env "QUEUE_CONNECTION" "database" .env
set_env "WHATSAPP_GATEWAY_URL" "http://127.0.0.1:3001" .env
set_env "WHATSAPP_GATEWAY_KEY" "${WHATSAPP_GATEWAY_KEY}" .env
set_env "WHATSAPP_DEFAULT_COUNTRY_CODE" "${WHATSAPP_DEFAULT_COUNTRY_CODE}" .env
set_env "WHATSAPP_MAX_MEDIA_MB" "${WHATSAPP_MAX_MEDIA_MB}" .env

step "6. App key, migrations, storage link"
grep -q "^APP_KEY=base64" .env || php artisan key:generate --force
php artisan migrate --force
php artisan storage:link 2>/dev/null || true

step "7. Building frontend assets (two separate builds — don't skip the second)"
cd "$DEPLOY_PATH"
npm install
npm run build

cd "$DEPLOY_PATH/packages/Crm/Admin"
npm install
npm run build
cd "$DEPLOY_PATH"

step "8. Setting up the WhatsApp Gateway"
cd "$DEPLOY_PATH/whatsapp-gateway"
npm install
cat > .env <<EOF
PORT=3001
GATEWAY_KEY=${WHATSAPP_GATEWAY_KEY}
EOF
cd "$DEPLOY_PATH"

step "9. Fixing file ownership and permissions (after builds, so build output is covered too)"
chown -R www-data:www-data "$DEPLOY_PATH"
find "$DEPLOY_PATH" -path "*/node_modules" -prune -o -path "*/.git" -prune -o -type f -exec chmod 664 {} \;
find "$DEPLOY_PATH" -path "*/node_modules" -prune -o -path "*/.git" -prune -o -type d -exec chmod 775 {} \;
chmod -R ug+rwx "$DEPLOY_PATH/storage" "$DEPLOY_PATH/bootstrap/cache"

step "10. Nginx site config"
cat > /etc/nginx/sites-available/crm <<EOF
server {
    listen 80;
    server_name ${DOMAIN};
    root ${DEPLOY_PATH}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;
    client_max_body_size 110M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF
ln -sf /etc/nginx/sites-available/crm /etc/nginx/sites-enabled/crm
nginx -t
systemctl reload nginx

step "11. SSL certificate (Let's Encrypt)"
CERTBOT_CMD="certbot --nginx -d ${DOMAIN} --non-interactive --agree-tos -m ${CERTBOT_EMAIL} --redirect"
$CERTBOT_CMD || echo "WARNING: certbot failed (usually DNS not propagated yet). Once 'dig ${DOMAIN}' shows this server's IP, just run:
  sudo ${CERTBOT_CMD}"

step "12. WhatsApp Gateway systemd service"
cat > /etc/systemd/system/whatsapp-gateway.service <<EOF
[Unit]
Description=WhatsApp Gateway (Baileys) for CRM
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=${DEPLOY_PATH}/whatsapp-gateway
ExecStart=$(command -v node) index.js
Restart=always
RestartSec=5
EnvironmentFile=${DEPLOY_PATH}/whatsapp-gateway/.env

[Install]
WantedBy=multi-user.target
EOF

step "13. Queue worker systemd service"
cat > /etc/systemd/system/crm-queue-worker.service <<EOF
[Unit]
Description=CRM Laravel Queue Worker
After=network.target mysql.service

[Service]
Type=simple
User=www-data
WorkingDirectory=${DEPLOY_PATH}
ExecStart=$(command -v php) artisan queue:work database --queue=default --tries=1 --timeout=90 --sleep=3
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable whatsapp-gateway crm-queue-worker
systemctl restart whatsapp-gateway crm-queue-worker

step "14. Scheduler cron entry"
CRON_LINE="* * * * * cd ${DEPLOY_PATH} && php artisan schedule:run >> /dev/null 2>&1"
EXISTING_CRON=$(crontab -u www-data -l 2>/dev/null || true)
FILTERED_CRON=$(printf '%s\n' "$EXISTING_CRON" | grep -vF "cd ${DEPLOY_PATH} && php artisan schedule:run" || true)
printf '%s\n%s\n' "$FILTERED_CRON" "$CRON_LINE" | crontab -u www-data -

step "15. Caching config for production"
php artisan config:cache
php artisan route:cache
php artisan view:cache

step "Saving generated credentials"
cat > "$SECRETS_FILE" <<EOF
CRM deployment secrets — generated $(date)
Domain:               https://${DOMAIN}
Database name:        ${DB_NAME}
Database user:        ${DB_USER}
Database password:    ${DB_PASS}
WhatsApp Gateway key: ${WHATSAPP_GATEWAY_KEY}
EOF
chmod 600 "$SECRETS_FILE"

echo -e "\n\033[1;32m✔ Deployment finished.\033[0m"
echo "Visit: https://${DOMAIN}"
echo "Credentials saved to ${SECRETS_FILE} (root-only) — copy them somewhere safe."
echo ""
echo "Two things this script could NOT do for you:"
echo "  1. Log in, go to WhatsApp Broadcast -> Link WhatsApp, and scan the QR with your phone."
echo "  2. This left MAIL_* settings untouched in .env — fill in real SMTP credentials there"
echo "     if you want outbound email notifications, then run: php artisan config:cache"

# ----------------------------------------------------------------------------
# FUTURE REDEPLOYS (after you push new code to the repo) — run manually:
#
#   cd /var/www/crm && git pull \
#     && composer install --no-dev --optimize-autoloader \
#     && php artisan migrate --force \
#     && npm run build \
#     && (cd packages/Crm/Admin && npm run build) \
#     && php artisan config:cache && php artisan route:cache && php artisan view:cache \
#     && sudo systemctl restart crm-queue-worker whatsapp-gateway
#
# The restart at the end matters — a running queue worker keeps old code in
# memory until restarted, same as on your local machine.
# ----------------------------------------------------------------------------
