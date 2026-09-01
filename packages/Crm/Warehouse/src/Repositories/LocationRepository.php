<?php

namespace Crm\Warehouse\Repositories;

use Crm\Core\Eloquent\Repository;

class LocationRepository extends Repository
{
    /**
     * Searchable fields
     */
    protected $fieldSearchable = [
        'name',
        'warehouse_id',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\Warehouse\Contracts\Location';
    }
}
