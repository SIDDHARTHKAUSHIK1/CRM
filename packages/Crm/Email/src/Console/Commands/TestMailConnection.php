<?php

namespace Crm\Email\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Webklex\IMAP\Facades\Client;

class TestMailConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-connection {--to= : Email address to send a test message to via SMTP}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test IMAP incoming and SMTP outgoing email connections with detailed diagnostics';

    /**
     * Handle the command.
     */
    public function handle()
    {
        $this->info('========================================================');
        $this->info('           CRM EMAIL CONNECTION DIAGNOSTICS            ');
        $this->info('========================================================');
        $this->newLine();

        $this->testImap();
        $this->newLine();
        $this->testSmtp();
        $this->newLine();

        $this->info('========================================================');
    }

    /**
     * Test IMAP connection.
     */
    protected function testImap()
    {
        $this->info('--- 1. TESTING INBOUND EMAIL (IMAP) ---');

        $driver = config('mail-receiver.default', 'webklex-imap');
        $this->line("Receiver Driver: <comment>{$driver}</comment>");

        $config = config('imap.accounts.default', []);
        
        // Merge with database core_config if available
        $config['host'] = core()->getConfigData('email.imap.account.host') ?: ($config['host'] ?? null);
        $config['port'] = core()->getConfigData('email.imap.account.port') ?: ($config['port'] ?? 993);
        $config['encryption'] = core()->getConfigData('email.imap.account.encryption') ?: ($config['encryption'] ?? 'ssl');
        $config['validate_cert'] = core()->getConfigData('email.imap.account.validate_cert') !== null 
            ? (bool) core()->getConfigData('email.imap.account.validate_cert') 
            : ($config['validate_cert'] ?? true);
        $config['username'] = core()->getConfigData('email.imap.account.username') ?: ($config['username'] ?? null);
        $config['password'] = core()->getConfigData('email.imap.account.password') ?: ($config['password'] ?? null);

        $this->line("IMAP Host:         <comment>{$config['host']}</comment>");
        $this->line("IMAP Port:         <comment>{$config['port']}</comment>");
        $this->line("IMAP Encryption:   <comment>{$config['encryption']}</comment>");
        $this->line("IMAP ValidateCert: <comment>" . ($config['validate_cert'] ? 'true' : 'false') . "</comment>");
        $this->line("IMAP Username:     <comment>{$config['username']}</comment>");
        $this->line("IMAP Password:     <comment>" . (empty($config['password']) ? '(NOT SET)' : '******** (' . strlen($config['password']) . ' chars)') . "</comment>");

        if (empty($config['host']) || empty($config['username']) || empty($config['password'])) {
            $this->error('✗ IMAP is missing Host, Username, or Password in .env / Settings!');
            return;
        }

        $this->line('Connecting to IMAP server...');

        try {
            $client = Client::make($config);
            $client->connect();

            if ($client->isConnected()) {
                $this->info('✔ SUCCESS: Connected to IMAP server successfully!');

                $folders = $client->getFolders();
                $this->line("Found <comment>" . count($folders) . "</comment> root folder(s):");
                foreach ($folders as $folder) {
                    try {
                        $count = $folder->messages()->all()->count();
                        $this->line(" - Folder: <info>{$folder->name}</info> ({$count} message(s))");
                    } catch (\Exception $fe) {
                        $this->line(" - Folder: <info>{$folder->name}</info>");
                    }
                }
            } else {
                $this->error('✗ FAILED: Client reported not connected.');
            }

            $client->disconnect();
        } catch (\Exception $e) {
            $this->error('✗ IMAP Connection Failed: ' . $e->getMessage());
            $this->warn('Tip: If using Hostinger, check that IMAP_HOST=imap.hostinger.com, IMAP_PORT=993, IMAP_ENCRYPTION=ssl.');
            $this->warn('Tip: If using Gmail, make sure you use an App Password (16 characters) and IMAP is enabled in Gmail settings.');
        }
    }

    /**
     * Test SMTP connection.
     */
    protected function testSmtp()
    {
        $this->info('--- 2. TESTING OUTBOUND EMAIL (SMTP) ---');

        $mailer = config('mail.default', 'smtp');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $encryption = config('mail.mailers.smtp.encryption');
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $this->line("Default Mailer:    <comment>{$mailer}</comment>");
        $this->line("SMTP Host:         <comment>{$host}</comment>");
        $this->line("SMTP Port:         <comment>{$port}</comment>");
        $this->line("SMTP Encryption:   <comment>{$encryption}</comment>");
        $this->line("SMTP Username:     <comment>{$username}</comment>");
        $this->line("SMTP Password:     <comment>" . (empty($password) ? '(NOT SET)' : '******** (' . strlen($password) . ' chars)') . "</comment>");
        $this->line("From Address:      <comment>{$fromAddress}</comment>");
        $this->line("From Name:         <comment>{$fromName}</comment>");

        if (empty($host) || empty($username) || empty($password)) {
            $this->error('✗ SMTP is missing Host, Username, or Password in .env!');
            return;
        }

        if (empty($fromAddress) || $fromAddress === 'laravel@example.com' || $fromAddress === 'hello@example.com') {
            $this->warn("⚠ Warning: MAIL_FROM_ADDRESS is set to '{$fromAddress}'. Mail servers often reject emails if the From address does not match your username ({$username})!");
        }

        $to = $this->option('to');
        if ($to) {
            $this->line("Attempting to send a test email to <comment>{$to}</comment>...");
            try {
                Mail::raw("Hello!\n\nThis is a test email sent from your CRM on " . date('Y-m-d H:i:s') . " to verify that SMTP outbound email is functioning correctly.", function ($message) use ($to, $fromAddress, $fromName) {
                    $message->to($to)
                        ->subject('CRM Test Email - Outbound SMTP Verified');
                });
                $this->info("✔ SUCCESS: Test email was accepted by SMTP server for delivery to {$to}!");
            } catch (\Exception $e) {
                $this->error('✗ SMTP Send Failed: ' . $e->getMessage());
                $this->warn('Tip: Ensure MAIL_PORT and MAIL_ENCRYPTION match (465 with ssl, or 587 with tls).');
            }
        } else {
            $this->line("To send a live test email, run: <comment>php artisan mail:test-connection --to=your_email@domain.com</comment>");
        }
    }
}
