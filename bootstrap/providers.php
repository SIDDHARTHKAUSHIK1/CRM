<?php

use App\Providers\AppServiceProvider;
use Barryvdh\DomPDF\ServiceProvider;
use Konekt\Concord\ConcordServiceProvider;
use Prettus\Repository\Providers\RepositoryServiceProvider;
use Crm\Activity\Providers\ActivityServiceProvider;
use Crm\Admin\Providers\AdminServiceProvider;
use Crm\Attribute\Providers\AttributeServiceProvider;
use Crm\Automation\Providers\WorkflowServiceProvider;
use Crm\Contact\Providers\ContactServiceProvider;
use Crm\Core\Providers\CoreServiceProvider;
use Crm\DataGrid\Providers\DataGridServiceProvider;
use Crm\DataTransfer\Providers\DataTransferServiceProvider;
use Crm\Email\Providers\EmailServiceProvider;
use Crm\EmailTemplate\Providers\EmailTemplateServiceProvider;
use Crm\GoogleContact\Providers\GoogleContactServiceProvider;
use Crm\Installer\Providers\InstallerServiceProvider;
use Crm\Lead\Providers\LeadServiceProvider;
use Crm\Marketing\Providers\MarketingServiceProvider;
use Crm\Product\Providers\ProductServiceProvider;
use Crm\Quote\Providers\QuoteServiceProvider;
use Crm\Tag\Providers\TagServiceProvider;
use Crm\User\Providers\UserServiceProvider;
use Crm\Warehouse\Providers\WarehouseServiceProvider;
use Crm\WebForm\Providers\WebFormServiceProvider;

return [
    /*
     * Package Service Providers...
     */
    ServiceProvider::class,
    ConcordServiceProvider::class,
    RepositoryServiceProvider::class,

    /*
     * Application Service Providers...
     */
    AppServiceProvider::class,

    /*
     * CRM Service Providers...
     */
    ActivityServiceProvider::class,
    AdminServiceProvider::class,
    AttributeServiceProvider::class,
    WorkflowServiceProvider::class,
    ContactServiceProvider::class,
    CoreServiceProvider::class,
    DataGridServiceProvider::class,
    DataTransferServiceProvider::class,
    EmailTemplateServiceProvider::class,
    EmailServiceProvider::class,
    GoogleContactServiceProvider::class,
    MarketingServiceProvider::class,
    InstallerServiceProvider::class,
    LeadServiceProvider::class,
    ProductServiceProvider::class,
    QuoteServiceProvider::class,
    TagServiceProvider::class,
    UserServiceProvider::class,
    WarehouseServiceProvider::class,
    WebFormServiceProvider::class,
    \Crm\WhatsApp\Providers\WhatsAppServiceProvider::class,
];
