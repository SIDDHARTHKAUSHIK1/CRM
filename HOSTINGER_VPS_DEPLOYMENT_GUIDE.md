# Deploying the WhatsApp CRM to your Hostinger VPS

You have a raw Ubuntu VPS (no site panel) with an existing project already running, Node.js already installed, and a domain you want to point at this new CRM. This guide adds the CRM as a second, independent site on the same server without touching your existing project's files, database, or Nginx config.

This project has **two services**, not one — keep both in mind throughout:

1. The **Laravel CRM** itself (PHP, MySQL) — needs to be public, on your domain, over HTTPS.
2. The **WhatsApp Gateway** (`whatsapp-gateway/`, a small Node.js service using Baileys) — needs to run continuously in the background, but only listens on `127.0.0.1:3001`. It is never exposed to the internet directly; only the Laravel app on the same server talks to it. Don't open port 3001 in the firewall or put it behind Nginx — it isn't meant to be reachable from outside the VPS at all.

Run every command below over SSH on the VPS unless it says otherwise.

## 0. First, check what's already on the server

Don't skip this — it tells you which install steps you actually need and confirms you won't be reinstalling something that would disrupt your existing project.

```bash
lsb_release -a                 # Ubuntu version
nginx -v 2>&1 || apache2 -v 2>&1   # which webserver is already running
php -v 2>&1                    # is PHP installed, and what version
mysql --version 2>&1 || mariadb --version 2>&1
node -v && npm -v              # confirm the Node.js you mentioned is already here
composer --version 2>&1
systemctl status nginx 2>&1 | head -5   # or apache2, whichever you have
```

This guide assumes **Nginx** (the far more common default on a hand-set-up Ubuntu VPS). If the check above shows Apache instead, everything is identical except the vhost section (step 6) — tell me and I'll give you the Apache equivalent, or search for "Laravel Apache VirtualHost" for the standard swap (`fastcgi_pass` → `SetHandler proxy:fcgi`).

Note the PHP version you get from `php -v` — this project needs **PHP 8.3 or newer** (`composer.json` requires `^8.3`). If your existing site runs on an older PHP via a different `php-fpm` version, that's fine — PHP supports multiple versions side by side on the same box, you'll just point this new site's Nginx config at the 8.3 socket specifically (covered in step 6).

## 1. Point your domain at the VPS

In your domain's DNS settings (wherever you registered/manage it — Hostinger's own DNS zone editor if it's a Hostinger domain), add:

```
Type: A
Name: @  (or a subdomain like crm if you'd rather use crm.yourdomain.com)
Value: <your VPS's public IP address>
TTL: 3600 (or leave default)
```

If you want `www.yourdomain.com` to also work, add a second `A` record for `www` pointing to the same IP (or a `CNAME` for `www` → `yourdomain.com`). DNS can take a few minutes to a few hours to propagate — you can move on to the next steps while it does, and just check `dig yourdomain.com` or `ping yourdomain.com` from your own machine before step 7 (SSL) to confirm it resolves to the VPS before requesting a certificate.

## 2. Install anything missing (PHP, MySQL, Composer)

Skip whichever of these your step-0 check already showed as installed.

**PHP 8.3 + required extensions:**

```bash
sudo apt update
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd php8.3-intl \
  php8.3-tokenizer php8.3-fileinfo
```

**Composer** (PHP dependency manager):

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

**MySQL** — if your existing project already uses MySQL/MariaDB (very likely, since that's the standard pairing with PHP/Laravel), you already have a server running; you don't need to install it again, you just need a **new, separate database and user** for this project (step 4). Only install it if step 0 showed no MySQL/MariaDB at all:

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

## 3. Get the code onto the server

Pick whichever you already used for your existing project so your workflow stays consistent — Git is strongly preferred since it makes future updates a one-line `git pull`.

```bash
sudo mkdir -p /var/www/crm
sudo chown $USER:$USER /var/www/crm
cd /var/www/crm
git clone <your repository URL> .
```

(No git remote yet? `scp -r` your local `CRM` folder to the server instead, or set one up now — either works, git just makes future deploys much easier.)

**Do not commit or upload your local `.env` file as-is** — it has your local dev database password and a real Gmail app password in it. You'll create a fresh `.env` for the server in step 5 with new credentials.

## 4. Create the database

```bash
sudo mysql
```

Inside the MySQL prompt:

```sql
CREATE DATABASE crm_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'crm_user'@'localhost' IDENTIFIED BY 'choose_a_strong_new_password_here';
GRANT ALL PRIVILEGES ON crm_production.* TO 'crm_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Use a **new** password here — don't reuse your local dev database password.

## 5. Configure `.env` for production

```bash
cd /var/www/crm
cp .env.example .env
nano .env
```

Set at least these values (leave everything else at its `.env.example` default unless you know you need to change it):

```
APP_NAME="Your CRM Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_production
DB_USERNAME=crm_user
DB_PASSWORD=choose_a_strong_new_password_here

QUEUE_CONNECTION=database

WHATSAPP_GATEWAY_URL=http://127.0.0.1:3001
WHATSAPP_GATEWAY_KEY=generate_a_long_random_string_here
WHATSAPP_DEFAULT_COUNTRY_CODE=91
WHATSAPP_DEFAULT_THROTTLE_SECONDS=20
WHATSAPP_MAX_MEDIA_MB=100
```

`APP_DEBUG=false` matters for a real deployment — leaving it `true` in production leaks stack traces (including file paths and config values) to anyone who hits an error page.

For `WHATSAPP_GATEWAY_KEY`, generate a real random value now that this is a public server (your local dev setup ran with this blank, which was fine on `127.0.0.1` only — worth locking down for a production box even though the gateway isn't internet-facing):

```bash
openssl rand -hex 32
```

Paste that value into both `WHATSAPP_GATEWAY_KEY` here **and** as `GATEWAY_KEY` in `whatsapp-gateway/.env` in step 9 — they must match exactly, since that's how the Laravel app authenticates to the gateway.

Then generate the app encryption key and finish install:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

## 6. File permissions

Laravel needs the webserver user (`www-data` on Ubuntu/Nginx) to be able to write to `storage/` and `bootstrap/cache/`:

```bash
sudo chown -R $USER:www-data /var/www/crm
sudo find /var/www/crm -type f -exec chmod 664 {} \;
sudo find /var/www/crm -type d -exec chmod 775 {} \;
sudo chmod -R ug+rwx /var/www/crm/storage /var/www/crm/bootstrap/cache
```

## 7. Build the frontend assets — there are TWO separate builds

This project has two independent Vite configs — most Laravel deploy guides only mention one, so don't stop after the first:

```bash
# Root app assets
cd /var/www/crm
npm install
npm run build

# Admin theme assets (Tailwind CSS, the WhatsApp Broadcast UI, everything under packages/Crm/Admin)
cd /var/www/crm/packages/Crm/Admin
npm install
npm run build
cd /var/www/crm
```

Both must complete successfully — the second one is the one that actually compiles the WhatsApp Broadcast pages' CSS (this is the same build step from the earlier "stale build" fix; it applies here too, just for a fresh server instead of a renamed folder).

## 8. Nginx server block for the new domain

Create a new site file — don't touch your existing project's config:

```bash
sudo nano /etc/nginx/sites-available/crm
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/crm/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Matches the WHATSAPP_MAX_MEDIA_MB=100 set above, so large brochure/video
    # uploads aren't rejected by Nginx before they even reach PHP.
    client_max_body_size 110M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

(If step 0 showed a different PHP-FPM socket path already in use for your other project, e.g. `php8.2-fpm.sock`, and you installed 8.3 fresh in step 2, use `php8.3-fpm.sock` here specifically — confirm the exact path with `ls /run/php/`.)

Enable it and reload Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/crm /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

`nginx -t` tests the config before reloading — if it reports an error, fix it before reloading, don't skip this check.

## 9. HTTPS via Let's Encrypt

Confirm DNS has propagated first (`dig yourdomain.com` should show your VPS's IP from your own machine), then:

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Certbot edits your new Nginx block to add the SSL config and redirect HTTP → HTTPS automatically, and sets up auto-renewal. Confirm with:

```bash
sudo certbot renew --dry-run
```

After this succeeds, update `.env`'s `APP_URL` to use `https://` if it isn't already, and re-run `php artisan config:clear` (see step 11) so Laravel picks it up.

## 10. WhatsApp Gateway — production `.env` and persistent process

```bash
cd /var/www/crm/whatsapp-gateway
npm install
nano .env
```

Create `whatsapp-gateway/.env` (this file doesn't exist yet in your project — the gateway currently just reads `process.env` directly, this creates it from the ground up for the server):

```
PORT=3001
GATEWAY_KEY=the_same_random_string_you_generated_in_step_5
```

The gateway must run continuously and restart itself if the VPS reboots or the process crashes — a plain `node index.js` in a terminal won't survive you disconnecting SSH. Use a systemd service:

```bash
sudo nano /etc/systemd/system/whatsapp-gateway.service
```

```ini
[Unit]
Description=WhatsApp Gateway (Baileys) for CRM
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/crm/whatsapp-gateway
ExecStart=/usr/bin/node index.js
Restart=always
RestartSec=5
EnvironmentFile=/var/www/crm/whatsapp-gateway/.env

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable whatsapp-gateway
sudo systemctl start whatsapp-gateway
sudo systemctl status whatsapp-gateway
```

`status` should show `active (running)`. Check its logs any time with `sudo journalctl -u whatsapp-gateway -f`.

**Linking WhatsApp on the server is a fresh link, not a copy of your local session.** Don't try to copy your local machine's `whatsapp-gateway/.session/` folder over — go to the CRM's "Link WhatsApp" page on the live domain once the site is up (step 13) and scan the QR code with the phone you want this deployment to send from, exactly like you did locally. The `.session/` folder will then be created fresh on the server and persists there across restarts (systemd's `Restart=always` won't wipe it — it's just a folder on disk).

## 11. Laravel queue worker — also a systemd service

Same idea as the gateway — `php artisan queue:work` has to run continuously, and a background `&` process or `nohup` won't survive reliably. Create:

```bash
sudo nano /etc/systemd/system/crm-queue-worker.service
```

```ini
[Unit]
Description=CRM Laravel Queue Worker
After=network.target mysql.service

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/crm
ExecStart=/usr/bin/php artisan queue:work database --queue=default --tries=1 --timeout=90 --sleep=3
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable crm-queue-worker
sudo systemctl start crm-queue-worker
sudo systemctl status crm-queue-worker
```

This replaces the `.bat` file / manual terminal you were using locally — same command, just supervised by systemd so it survives reboots and restarts itself if it ever crashes. **Remember this rule still applies**: after any future code deploy (`git pull` + changes), run `sudo systemctl restart crm-queue-worker` — a running worker keeps the old code in memory until restarted, exactly like on your local machine.

## 12. Laravel scheduler — a real cron entry this time

Locally you used `php artisan schedule:work` in an open terminal; on a server, use actual cron (this is the standard, correct way to run Laravel's scheduler in production):

```bash
sudo crontab -u www-data -e
```

Add this single line:

```
* * * * * cd /var/www/crm && php artisan schedule:run >> /dev/null 2>&1
```

This fires every minute and lets Laravel's own scheduler decide what actually needs to run (including the `whatsapp:process-campaigns` safety-net command from the earlier fix, and this project's existing `inbound-emails:process` schedule) — you don't need a separate cron line per command.

## 13. Final config cache and smoke test

```bash
cd /var/www/crm
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

(Re-run these three any time you change `.env` or routes after this point — cached config means Laravel stops reading `.env` directly until you clear/rebuild the cache: `php artisan config:clear` then `config:cache` again.)

Now verify, in order:

- Visit `https://yourdomain.com` — the login page should load over HTTPS with a valid padlock, and your existing project on this same VPS should be completely unaffected (different domain, different Nginx file, different database).
- Log in and open WhatsApp Broadcast → Link WhatsApp, confirm the QR code renders (this exercises the gateway systemd service end-to-end) and scan it with a real phone.
- Create a small test campaign (1-2 numbers you control) with an image and a video brochure, start it, and confirm the message actually arrives — this exercises the queue worker service, the gateway service, and the `client_max_body_size`/`WHATSAPP_MAX_MEDIA_MB` settings together.
- `sudo systemctl status whatsapp-gateway crm-queue-worker nginx php8.3-fpm` — confirm all four show `active (running)`.
- Reboot the VPS once (`sudo reboot`, if you can afford a brief outage window) and confirm both custom services come back up on their own afterward (`enable` in steps 10 and 11 is what makes this work) — this is the one thing that's easy to get wrong and only shows up the next time the server restarts for any reason.
