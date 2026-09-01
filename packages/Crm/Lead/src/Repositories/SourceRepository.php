<?php

namespace Crm\Lead\Repositories;

use Crm\Core\Eloquent\Repository;

class SourceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\Lead\Contracts\Source';
    }
}
