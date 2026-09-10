@echo off
title CRM Local Development Environment
echo ===================================================
echo Starting Real Estate CRM Local Environment
echo ===================================================

echo [1/3] Starting WhatsApp Gateway Microservice (Port 3001)...
start "WhatsApp Gateway (Node.js)" cmd /k "cd /d \"%~dp0whatsapp-gateway\" && node index.js"

echo [2/3] Starting Laravel Development Server (Port 8000)...
start "Laravel CRM Server" cmd /k "cd /d \"%~dp0\" && php artisan serve --host=127.0.0.1 --port=8000"

echo [3/3] Starting Laravel Queue Worker (for WhatsApp Broadcasts)...
start "Laravel Queue Worker" cmd /k "cd /d \"%~dp0\" && php artisan queue:work --sleep=2 --tries=3"

echo ===================================================
echo All services are now running:
echo - Laravel CRM:         http://127.0.0.1:8000/admin/whatsapp
echo - WhatsApp Gateway:    http://127.0.0.1:3001
echo - Queue Worker:        Active (Processing background broadcasts)
echo ===================================================
