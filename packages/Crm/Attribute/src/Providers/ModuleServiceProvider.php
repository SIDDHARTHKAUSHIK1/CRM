<?php

namespace Crm\Attribute\Providers;

use Crm\Attribute\Models\Attribute;
use Crm\Attribute\Models\AttributeOption;
use Crm\Attribute\Models\AttributeValue;
use Crm\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var array{
     *  0: class-string<Attribute>,
     *  1: class-string<AttributeOption>,
     *  2: class-string<AttributeValue>
     * }
     */
    protected $models = [
        Attribute::class,
        AttributeOption::class,
        AttributeValue::class,
    ];
}
