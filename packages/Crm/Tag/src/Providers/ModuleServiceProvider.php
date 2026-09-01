<?php

namespace Crm\Tag\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\Tag\Models\Tag;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Tag::class,
    ];
}
