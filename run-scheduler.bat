@echo off
cd /d "%~dp0"
echo =======================================================
echo Starting Laravel CRM Scheduler Worker...
echo Runs scheduled jobs (inbound emails, campaign recovery).
echo =======================================================
php artisan schedule:work
pause
