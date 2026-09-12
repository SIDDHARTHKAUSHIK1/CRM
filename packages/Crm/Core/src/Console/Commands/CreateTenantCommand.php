<?php

namespace Crm\Core\Console\Commands;

use Illuminate\Console\Command;
use Crm\Core\Services\TenantProvisioner;
use Crm\User\Models\User;

class CreateTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create 
                            {name : The display name of the tenant/organization}
                            {email : The email address for the initial administrator}
                            {password : The initial password for the administrator}
                            {--admin-name= : Optional custom name for the administrator user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and provision a new isolated tenant with default CRM pipelines, attributes, templates, and administrator account';

    /**
     * Execute the console command.
     */
    public function handle(TenantProvisioner $provisioner): int
    {
        $name = $this->argument('name');
        $email = strtolower(trim($this->argument('email')));
        $password = $this->argument('password');
        $adminName = $this->option('admin-name');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address: [{$email}]");
            return 1;
        }

        if (strlen($password) < 6) {
            $this->error('Password must be at least 6 characters.');
            return 1;
        }

        // Check global email uniqueness across all users
        if (User::withoutGlobalScopes()->where('email', $email)->exists()) {
            $this->error("User with email [{$email}] already exists in the system.");
            return 1;
        }

        $this->info("Provisioning new tenant [{$name}]...");

        try {
            $result = $provisioner->provision(
                name: $name,
                adminEmail: $email,
                adminPassword: $password,
                adminName: $adminName
            );

            $tenant = $result['tenant'];
            $user = $result['user'];

            $this->info("--------------------------------------------------");
            $this->info("Tenant provisioned successfully!");
            $this->info("Tenant ID:    {$tenant->id}");
            $this->info("Tenant Name:  {$tenant->name}");
            $this->info("Admin User:   {$user->name} ({$user->email})");
            $this->info("Role:         Administrator (Full Access)");
            $this->info("Status:       Active");
            $this->info("--------------------------------------------------");

            return 0;
        } catch (\Throwable $e) {
            $this->error("Failed to provision tenant: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
