<?php

namespace Crm\Contact\Providers;

use Crm\Contact\Models\Organization;
use Crm\Contact\Models\Person;
use Crm\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Person::class,
        Organization::class,
    ];
}
