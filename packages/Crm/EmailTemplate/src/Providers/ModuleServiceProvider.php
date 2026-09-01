<?php

namespace Crm\EmailTemplate\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\EmailTemplate\Models\EmailTemplate;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        EmailTemplate::class,
    ];
}
