<?php

namespace Crm\DataTransfer\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\DataTransfer\Models\Import;
use Crm\DataTransfer\Models\ImportBatch;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Define models to map with repository interfaces.
     *
     * @var array
     */
    protected $models = [
        Import::class,
        ImportBatch::class,
    ];
}
