@echo off
cd /d "%~dp0"
echo =======================================================
echo Starting Laravel CRM WhatsApp Queue Worker...
echo Leave this window open while broadcasting messages.
echo =======================================================
php artisan queue:work database --queue=default --tries=1 --timeout=90 -v
pause
