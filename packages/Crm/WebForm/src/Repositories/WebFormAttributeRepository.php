<?php

namespace Crm\WebForm\Repositories;

use Crm\Core\Eloquent\Repository;

class WebFormAttributeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\WebForm\Contracts\WebFormAttribute';
    }
}
