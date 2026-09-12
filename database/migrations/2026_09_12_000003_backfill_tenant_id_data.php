<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The list of tables to backfill with tenant_id = 1.
     *
     * @var array
     */
    protected array $tables = [
        'users',
        'roles',
        'groups',
        'leads',
        'lead_pipelines',
        'lead_pipeline_stages',
        'lead_sources',
        'lead_types',
        'lead_tags',
        'persons',
        'organizations',
        'person_tags',
        'products',
        'product_inventories',
        'product_tags',
        'quotes',
        'activities',
        'attributes',
        'attribute_options',
        'email_templates',
        'web_forms',
        'workflows',
        'webhooks',
        'marketing_events',
        'marketing_campaigns',
        'imports',
        'import_batches',
        'google_contact_accounts',
        'contact_export_batches',
        'contact_export_batch_items',
        'tags',
        'warehouses',
        'warehouse_locations',
        'whatsapp_campaigns',
        'whatsapp_do_not_contacts',
        'whatsapp_campaign_recipients',
        'datagrid_saved_filters',
        'core_config',
        'emails',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure default tenant 1 exists
        $defaultTenant = DB::table('tenants')->where('id', 1)->first();
        if (! $defaultTenant) {
            DB::table('tenants')->insert([
                'id'         => 1,
                'name'       => 'Default Tenant',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                DB::table($tableName)
                    ->whereNull('tenant_id')
                    ->update(['tenant_id' => 1]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data backfill reversal is a no-op
    }
};
