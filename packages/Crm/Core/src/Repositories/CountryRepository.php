<?php

namespace Crm\Core\Repositories;

use Prettus\Repository\Traits\CacheableRepository;
use Crm\Core\Eloquent\Repository;

class CountryRepository extends Repository
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\Core\Contracts\Country';
    }
}
