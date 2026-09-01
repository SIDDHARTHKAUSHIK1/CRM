<?php

namespace Crm\WebForm\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\WebForm\Models\WebForm;
use Crm\WebForm\Models\WebFormAttribute;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        WebForm::class,
        WebFormAttribute::class,
    ];
}
