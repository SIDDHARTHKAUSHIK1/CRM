<?php

namespace Crm\Warehouse\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\Warehouse\Models\Location;
use Crm\Warehouse\Models\Warehouse;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Location::class,
        Warehouse::class,
    ];
}
