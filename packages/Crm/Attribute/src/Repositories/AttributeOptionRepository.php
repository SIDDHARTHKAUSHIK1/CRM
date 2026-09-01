<?php

namespace Crm\Attribute\Repositories;

use Crm\Core\Eloquent\Repository;

class AttributeOptionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\Attribute\Contracts\AttributeOption';
    }
}
