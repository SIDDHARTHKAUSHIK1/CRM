<?php

namespace Crm\Activity\Providers;

use Crm\Activity\Models\Activity;
use Crm\Activity\Models\File;
use Crm\Activity\Models\Participant;
use Crm\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Activity::class,
        File::class,
        Participant::class,
    ];
}
