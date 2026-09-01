<?php

namespace Crm\Product\Repositories;

use Crm\Core\Eloquent\Repository;

class ProductInventoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\Product\Contracts\ProductInventory';
    }
}
