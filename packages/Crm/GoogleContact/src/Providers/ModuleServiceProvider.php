<?php

namespace Crm\GoogleContact\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\GoogleContact\Models\ContactExportBatch;
use Crm\GoogleContact\Models\ContactExportBatchItem;
use Crm\GoogleContact\Models\GoogleContactAccount;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        GoogleContactAccount::class,
        ContactExportBatch::class,
        ContactExportBatchItem::class,
    ];
}
