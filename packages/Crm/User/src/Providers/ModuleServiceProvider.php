<?php

namespace Crm\User\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\User\Models\Group;
use Crm\User\Models\Role;
use Crm\User\Models\User;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Group::class,
        Role::class,
        User::class,
    ];
}
