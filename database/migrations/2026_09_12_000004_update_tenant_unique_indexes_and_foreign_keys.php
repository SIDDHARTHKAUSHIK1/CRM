<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables receiving foreign key constraints to tenants(id).
     *
     * @var array
     */
    protected array $foreignKeyTables = [
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
        // 1. Update unique constraints to be composite with tenant_id

        // attributes: code + entity_type -> tenant_id + code + entity_type
        if (Schema::hasTable('attributes')) {
            $this->dropIndexIfExists('attributes', 'attributes_code_entity_type_unique');
            Schema::table('attributes', function (Blueprint $table) {
                $table->unique(['tenant_id', 'code', 'entity_type'], 'attributes_tenant_code_entity_unique');
            });
        }

        // email_templates: name -> tenant_id + name
        if (Schema::hasTable('email_templates')) {
            $this->dropIndexIfExists('email_templates', 'email_templates_name_unique');
            Schema::table('email_templates', function (Blueprint $table) {
                $table->unique(['tenant_id', 'name'], 'email_templates_tenant_name_unique');
            });
        }

        // lead_pipelines: name -> tenant_id + name
        if (Schema::hasTable('lead_pipelines')) {
            $this->dropIndexIfExists('lead_pipelines', 'lead_pipelines_name_unique');
            Schema::table('lead_pipelines', function (Blueprint $table) {
                $table->unique(['tenant_id', 'name'], 'lead_pipelines_tenant_name_unique');
            });
        }

        // groups: name -> tenant_id + name
        if (Schema::hasTable('groups')) {
            $this->dropIndexIfExists('groups', 'groups_name_unique');
            Schema::table('groups', function (Blueprint $table) {
                $table->unique(['tenant_id', 'name'], 'groups_tenant_name_unique');
            });
        }

        // organizations: name -> tenant_id + name
        if (Schema::hasTable('organizations')) {
            $this->dropIndexIfExists('organizations', 'organizations_name_unique');
            Schema::table('organizations', function (Blueprint $table) {
                $table->unique(['tenant_id', 'name'], 'organizations_tenant_name_unique');
            });
        }

        // products: sku -> tenant_id + sku
        if (Schema::hasTable('products')) {
            $this->dropIndexIfExists('products', 'products_sku_unique');
            Schema::table('products', function (Blueprint $table) {
                $table->unique(['tenant_id', 'sku'], 'products_tenant_sku_unique');
            });
        }

        // whatsapp_do_not_contacts: phone_e164 -> tenant_id + phone_e164
        if (Schema::hasTable('whatsapp_do_not_contacts')) {
            $this->dropIndexIfExists('whatsapp_do_not_contacts', 'whatsapp_do_not_contacts_phone_e164_unique');
            Schema::table('whatsapp_do_not_contacts', function (Blueprint $table) {
                $table->unique(['tenant_id', 'phone_e164'], 'whatsapp_dnc_tenant_phone_unique');
            });
        }

        // 2. Add foreign key constraints referencing tenants(id)
        foreach ($this->foreignKeyTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                try {
                    Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                        $table->foreign('tenant_id')
                            ->references('id')
                            ->on('tenants')
                            ->cascadeOnDelete();
                    });
                } catch (\Throwable $e) {
                    // Foreign key may already exist or DB driver may not support it
                }
            }
        }
    }

    /**
     * Helper to safely drop an index if it exists.
     */
    protected function dropIndexIfExists(string $table, string $indexName): void
    {
        $existing = DB::select("
            SELECT INDEX_NAME 
            FROM INFORMATION_SCHEMA.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = ? 
              AND INDEX_NAME = ?
            LIMIT 1
        ", [$table, $indexName]);

        if (! empty($existing)) {
            Schema::table($table, function (Blueprint $blueprint) use ($indexName) {
                $blueprint->dropUnique($indexName);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->foreignKeyTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                try {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->dropForeign(['tenant_id']);
                    });
                } catch (\Throwable $e) {}
            }
        }
    }
};
