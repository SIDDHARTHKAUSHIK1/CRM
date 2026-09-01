<?php

namespace Crm\Lead\Repositories;

use Crm\Core\Eloquent\Repository;

class StageRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\Lead\Contracts\Stage';
    }
}
