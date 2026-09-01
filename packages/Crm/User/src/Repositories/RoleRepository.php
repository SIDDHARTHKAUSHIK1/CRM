<?php

namespace Crm\User\Repositories;

use Crm\Core\Eloquent\Repository;

class RoleRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\User\Contracts\Role';
    }
}
