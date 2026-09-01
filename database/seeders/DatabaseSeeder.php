<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Crm\Installer\Database\Seeders\DatabaseSeeder as CrmDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CrmDatabaseSeeder::class);
        $this->call(DemoDataSeeder::class);
    }
}
