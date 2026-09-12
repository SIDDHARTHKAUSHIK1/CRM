<?php

namespace Crm\Core\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Crm\Attribute\Models\Attribute;
use Crm\Core\Models\Tenant;
use Crm\EmailTemplate\Models\EmailTemplate;
use Crm\Lead\Models\Pipeline;
use Crm\Lead\Models\Source;
use Crm\Lead\Models\Type;
use Crm\User\Models\Role;
use Crm\User\Models\User;

class TenantProvisioner
{
    /**
     * Provision a new tenant with initial administrator and default configuration.
     *
     * @param  string  $name
     * @param  string  $adminEmail
     * @param  string  $adminPassword
     * @param  string|null  $adminName
     * @return array
     */
    public function provision(
        string $name,
        string $adminEmail,
        string $adminPassword,
        ?string $adminName = null
    ): array {
        return DB::transaction(function () use ($name, $adminEmail, $adminPassword, $adminName) {
            // 1. Create Tenant
            $tenant = Tenant::create([
                'name'   => $name,
                'status' => 'active',
            ]);

            // 2. Create Administrator and Employee Roles for this Tenant
            $adminRole = Role::create([
                'tenant_id'       => $tenant->id,
                'name'            => 'Administrator',
                'description'     => 'Administrator role for ' . $name,
                'permission_type' => 'all',
                'permissions'     => null,
            ]);

            $employeePermissions = [
                'dashboard',
                'leads', 'leads.create', 'leads.create.quick-create', 'leads.view', 'leads.edit', 'leads.delete',
                'quotes', 'quotes.create', 'quotes.mail', 'quotes.edit', 'quotes.print', 'quotes.delete',
                'whatsapp', 'whatsapp.create', 'whatsapp.manage', 'whatsapp.delete',
                'mail', 'mail.inbox', 'mail.draft', 'mail.outbox', 'mail.sent', 'mail.trash', 'mail.compose', 'mail.compose.quick-create', 'mail.view', 'mail.edit', 'mail.delete',
                'activities', 'activities.create', 'activities.edit', 'activities.delete',
                'contacts', 'contacts.persons', 'contacts.persons.create', 'contacts.persons.create.quick-create', 'contacts.persons.edit', 'contacts.persons.delete', 'contacts.persons.export_google', 'contacts.persons.view', 'contacts.organizations', 'contacts.organizations.create', 'contacts.organizations.create.quick-create', 'contacts.organizations.edit', 'contacts.organizations.delete',
                'products', 'products.create', 'products.create.quick-create', 'products.edit', 'products.delete', 'products.view',
                'settings',
                'settings.user', 'settings.user.groups', 'settings.user.groups.create', 'settings.user.groups.edit', 'settings.user.groups.delete',
                'settings.user.roles', 'settings.user.roles.create', 'settings.user.roles.edit', 'settings.user.roles.delete',
                'settings.user.users', 'settings.user.users.create', 'settings.user.users.edit', 'settings.user.users.delete',
                'settings.lead', 'settings.lead.pipelines', 'settings.lead.pipelines.create', 'settings.lead.pipelines.edit', 'settings.lead.pipelines.delete',
                'settings.lead.sources', 'settings.lead.sources.create', 'settings.lead.sources.edit', 'settings.lead.sources.delete',
                'settings.lead.types', 'settings.lead.types.create', 'settings.lead.types.edit', 'settings.lead.types.delete',
                'settings.inventory', 'settings.inventory.warehouse', 'settings.inventory.warehouse.create', 'settings.inventory.warehouse.edit', 'settings.inventory.warehouse.delete',
                'settings.automation', 'settings.automation.attributes', 'settings.automation.attributes.create', 'settings.automation.attributes.edit', 'settings.automation.attributes.delete',
                'settings.automation.email_templates', 'settings.automation.email_templates.create', 'settings.automation.email_templates.edit', 'settings.automation.email_templates.delete',
                'settings.automation.workflows', 'settings.automation.workflows.create', 'settings.automation.workflows.edit', 'settings.automation.workflows.delete',
                'settings.automation.events', 'settings.automation.events.create', 'settings.automation.events.edit', 'settings.automation.events.delete',
                'settings.automation.campaigns', 'settings.automation.campaigns.create', 'settings.automation.campaigns.edit', 'settings.automation.campaigns.delete',
                'settings.automation.webhooks', 'settings.automation.webhooks.create', 'settings.automation.webhooks.edit', 'settings.automation.webhooks.delete',
                'settings.automation.data_transfer', 'settings.automation.data_transfer.imports', 'settings.automation.data_transfer.imports.create', 'settings.automation.data_transfer.imports.edit', 'settings.automation.data_transfer.imports.delete', 'settings.automation.data_transfer.imports.import',
                'settings.other_settings', 'settings.other_settings.tags', 'settings.other_settings.tags.create', 'settings.other_settings.tags.edit', 'settings.other_settings.tags.delete',
                'settings.other_settings.web_forms', 'settings.other_settings.web_forms.view', 'settings.other_settings.web_forms.create', 'settings.other_settings.web_forms.edit', 'settings.other_settings.web_forms.delete',
                'settings.other_settings.google_contacts',
                'configuration',
                'help',
            ];

            Role::create([
                'tenant_id'       => $tenant->id,
                'name'            => 'Employee',
                'description'     => 'Employee role for ' . $name,
                'permission_type' => 'custom',
                'permissions'     => $employeePermissions,
            ]);

            // 3. Create Administrator User
            $adminUser = User::create([
                'tenant_id'       => $tenant->id,
                'role_id'         => $adminRole->id,
                'name'            => $adminName ?: "{$name} Admin",
                'email'           => $adminEmail,
                'password'        => bcrypt($adminPassword),
                'password_plain'  => Crypt::encryptString($adminPassword),
                'status'          => 1,
                'view_permission' => 'global',
            ]);

            // 4. Seed Pipelines & Stages
            $defaultPipelines = Pipeline::withoutGlobalScopes()
                ->where('tenant_id', 1)
                ->with('stages')
                ->get();

            if ($defaultPipelines->isNotEmpty()) {
                foreach ($defaultPipelines as $origPipeline) {
                    $newPipeline = Pipeline::create([
                        'tenant_id'   => $tenant->id,
                        'name'        => $origPipeline->name,
                        'is_default'  => $origPipeline->is_default,
                        'rotten_days' => $origPipeline->rotten_days,
                    ]);

                    foreach ($origPipeline->stages as $origStage) {
                        $newPipeline->stages()->create([
                            'tenant_id'   => $tenant->id,
                            'code'        => $origStage->code,
                            'name'        => $origStage->name,
                            'probability' => $origStage->probability,
                            'sort_order'  => $origStage->sort_order,
                        ]);
                    }
                }
            } else {
                $pipeline = Pipeline::create([
                    'tenant_id'   => $tenant->id,
                    'name'        => 'Default',
                    'is_default'  => 1,
                    'rotten_days' => 30,
                ]);

                $stages = [
                    ['code' => 'new', 'name' => 'New', 'probability' => 100, 'sort_order' => 1],
                    ['code' => 'follow-up', 'name' => 'Follow Up', 'probability' => 100, 'sort_order' => 2],
                    ['code' => 'prospect', 'name' => 'Prospect', 'probability' => 100, 'sort_order' => 3],
                    ['code' => 'negotiation', 'name' => 'Negotiation', 'probability' => 100, 'sort_order' => 4],
                    ['code' => 'won', 'name' => 'Won', 'probability' => 100, 'sort_order' => 5],
                    ['code' => 'lost', 'name' => 'Lost', 'probability' => 0, 'sort_order' => 6],
                ];

                foreach ($stages as $stageData) {
                    $pipeline->stages()->create(array_merge($stageData, ['tenant_id' => $tenant->id]));
                }
            }

            // 5. Seed Sources
            $defaultSources = Source::withoutGlobalScopes()->where('tenant_id', 1)->get();
            if ($defaultSources->isNotEmpty()) {
                foreach ($defaultSources as $source) {
                    Source::create([
                        'tenant_id' => $tenant->id,
                        'name'      => $source->name,
                    ]);
                }
            } else {
                foreach (['Email', 'Google', 'Direct', 'Web Form', 'Referral'] as $sourceName) {
                    Source::create(['tenant_id' => $tenant->id, 'name' => $sourceName]);
                }
            }

            // 6. Seed Types
            $defaultTypes = Type::withoutGlobalScopes()->where('tenant_id', 1)->get();
            if ($defaultTypes->isNotEmpty()) {
                foreach ($defaultTypes as $type) {
                    Type::create([
                        'tenant_id' => $tenant->id,
                        'name'      => $type->name,
                    ]);
                }
            } else {
                foreach (['Existing Business', 'New Business'] as $typeName) {
                    Type::create(['tenant_id' => $tenant->id, 'name' => $typeName]);
                }
            }

            // 7. Seed Attributes and Options
            $defaultAttributes = Attribute::withoutGlobalScopes()
                ->where('tenant_id', 1)
                ->with('options')
                ->get();

            foreach ($defaultAttributes as $origAttr) {
                $newAttr = Attribute::create([
                    'tenant_id'       => $tenant->id,
                    'code'            => $origAttr->code,
                    'name'            => $origAttr->name,
                    'type'            => $origAttr->type,
                    'lookup_type'     => $origAttr->lookup_type,
                    'entity_type'     => $origAttr->entity_type,
                    'sort_order'      => $origAttr->sort_order,
                    'validation'      => $origAttr->validation,
                    'is_required'     => $origAttr->is_required,
                    'is_unique'       => $origAttr->is_unique,
                    'quick_add'       => $origAttr->quick_add,
                    'is_user_defined' => $origAttr->is_user_defined,
                ]);

                foreach ($origAttr->options as $origOption) {
                    $newAttr->options()->create([
                        'tenant_id'  => $tenant->id,
                        'name'       => $origOption->name,
                        'sort_order' => $origOption->sort_order,
                    ]);
                }
            }

            // 8. Seed Email Templates
            $defaultTemplates = EmailTemplate::withoutGlobalScopes()->where('tenant_id', 1)->get();
            foreach ($defaultTemplates as $origTpl) {
                EmailTemplate::create([
                    'tenant_id' => $tenant->id,
                    'name'      => $origTpl->name,
                    'subject'   => $origTpl->subject,
                    'content'   => $origTpl->content,
                    'status'    => $origTpl->status,
                ]);
            }

            return [
                'tenant' => $tenant,
                'user'   => $adminUser,
                'role'   => $adminRole,
            ];
        });
    }
}
