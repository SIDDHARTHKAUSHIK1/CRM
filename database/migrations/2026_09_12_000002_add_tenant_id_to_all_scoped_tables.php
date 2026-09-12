<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The list of tables that belong to a tenant.
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
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Check if 'id' exists as a column to place after 'id', else add without after
                    if (Schema::hasColumn($tableName, 'id')) {
                        $table->unsignedInteger('tenant_id')->nullable()->after('id')->index();
                    } else {
                        $table->unsignedInteger('tenant_id')->nullable()->index();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('tenant_id');
                });
            }
        }
    }
};
