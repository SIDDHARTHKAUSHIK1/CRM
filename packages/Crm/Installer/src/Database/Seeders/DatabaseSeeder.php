<?php

namespace Crm\Installer\Database\Seeders;

use Illuminate\Database\Seeder;
use Crm\Installer\Database\Seeders\Attribute\DatabaseSeeder as AttributeSeeder;
use Crm\Installer\Database\Seeders\Core\DatabaseSeeder as CoreSeeder;
use Crm\Installer\Database\Seeders\EmailTemplate\DatabaseSeeder as EmailTemplateSeeder;
use Crm\Installer\Database\Seeders\Lead\DatabaseSeeder as LeadSeeder;
use Crm\Installer\Database\Seeders\User\DatabaseSeeder as UserSeeder;
use Crm\Installer\Database\Seeders\Workflow\DatabaseSeeder as WorkflowSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call(AttributeSeeder::class, false, ['parameters' => $parameters]);
        $this->call(CoreSeeder::class, false, ['parameters' => $parameters]);
        $this->call(EmailTemplateSeeder::class, false, ['parameters' => $parameters]);
        $this->call(LeadSeeder::class, false, ['parameters' => $parameters]);
        $this->call(UserSeeder::class, false, ['parameters' => $parameters]);
        $this->call(WorkflowSeeder::class, false, ['parameters' => $parameters]);
    }
}
