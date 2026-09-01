<?php

namespace Crm\Automation\Providers;

use Crm\Automation\Models\Webhook;
use Crm\Automation\Models\Workflow;
use Crm\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Define the modals to map with this module.
     *
     * @var array
     */
    protected $models = [
        Workflow::class,
        Webhook::class,
    ];
}
