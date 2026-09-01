<?php

namespace Crm\Product\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\Product\Models\Product;
use Crm\Product\Models\ProductInventory;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Product::class,
        ProductInventory::class,
    ];
}
