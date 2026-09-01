<?php

namespace Crm\Core\Providers;

use Crm\Core\Models\CoreConfig;
use Crm\Core\Models\Country;
use Crm\Core\Models\CountryState;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        CoreConfig::class,
        Country::class,
        CountryState::class,
    ];
}
