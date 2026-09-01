<?php

namespace Crm\DataGrid\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\DataGrid\Models\SavedFilter;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        SavedFilter::class,
    ];
}
