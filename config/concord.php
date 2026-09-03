<?php

use Crm\Admin\Providers\ModuleServiceProvider as AdminModuleServiceProvider;
use Crm\Attribute\Providers\ModuleServiceProvider as AttributeModuleServiceProvider;
use Crm\Automation\Providers\ModuleServiceProvider as AutomationModuleServiceProvider;
use Crm\Contact\Providers\ModuleServiceProvider as ContactModuleServiceProvider;
use Crm\Core\Providers\ModuleServiceProvider as CoreModuleServiceProvider;
use Crm\DataGrid\Providers\ModuleServiceProvider as DataGridModuleServiceProvider;
use Crm\DataTransfer\Providers\ModuleServiceProvider as DataTransferModuleServiceProvider;
use Crm\Email\Providers\ModuleServiceProvider as EmailModuleServiceProvider;
use Crm\EmailTemplate\Providers\ModuleServiceProvider as EmailTemplateModuleServiceProvider;
use Crm\Lead\Providers\ModuleServiceProvider as LeadModuleServiceProvider;
use Crm\Product\Providers\ModuleServiceProvider as ProductModuleServiceProvider;
use Crm\Quote\Providers\ModuleServiceProvider as QuoteModuleServiceProvider;
use Crm\Tag\Providers\ModuleServiceProvider as TagModuleServiceProvider;
use Crm\User\Providers\ModuleServiceProvider as UserModuleServiceProvider;
use Crm\Warehouse\Providers\ModuleServiceProvider as WarehouseModuleServiceProvider;
use Crm\WebForm\Providers\ModuleServiceProvider as WebFormModuleServiceProvider;
use Crm\WhatsApp\Providers\ModuleServiceProvider as WhatsAppModuleServiceProvider;

return [
    'modules' => [
        DataTransferModuleServiceProvider::class,
        AdminModuleServiceProvider::class,
        AttributeModuleServiceProvider::class,
        AutomationModuleServiceProvider::class,
        ContactModuleServiceProvider::class,
        CoreModuleServiceProvider::class,
        DataGridModuleServiceProvider::class,
        EmailTemplateModuleServiceProvider::class,
        EmailModuleServiceProvider::class,
        LeadModuleServiceProvider::class,
        ProductModuleServiceProvider::class,
        QuoteModuleServiceProvider::class,
        TagModuleServiceProvider::class,
        UserModuleServiceProvider::class,
        WarehouseModuleServiceProvider::class,
        WebFormModuleServiceProvider::class,
        DataTransferModuleServiceProvider::class,
        WhatsAppModuleServiceProvider::class,
    ],

    'register_route_models' => true,
];
