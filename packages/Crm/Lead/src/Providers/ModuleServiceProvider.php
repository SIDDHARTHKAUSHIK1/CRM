<?php

namespace Crm\Lead\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\Lead\Models\Lead;
use Crm\Lead\Models\Pipeline;
use Crm\Lead\Models\Product;
use Crm\Lead\Models\Source;
use Crm\Lead\Models\Stage;
use Crm\Lead\Models\Type;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Lead::class,
        Pipeline::class,
        Product::class,
        Source::class,
        Stage::class,
        Type::class,
    ];
}
