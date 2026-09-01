<?php

namespace Crm\Email\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\Email\Models\Attachment;
use Crm\Email\Models\Email;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Email::class,
        Attachment::class,
    ];
}
