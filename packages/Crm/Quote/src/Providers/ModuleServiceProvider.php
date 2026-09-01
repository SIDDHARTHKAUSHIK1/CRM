<?php

namespace Crm\Quote\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\Quote\Models\Quote;
use Crm\Quote\Models\QuoteItem;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Quote::class,
        QuoteItem::class,
    ];
}
