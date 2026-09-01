<?php

namespace Crm\Marketing\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\Marketing\Models\Campaign;
use Crm\Marketing\Models\Event;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Define the module's array.
     *
     * @var array
     */
    protected $models = [
        Event::class,
        Campaign::class,
    ];
}
