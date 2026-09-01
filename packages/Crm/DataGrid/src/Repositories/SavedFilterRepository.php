<?php

namespace Crm\DataGrid\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\DataGrid\Contracts\SavedFilter;

class SavedFilterRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return SavedFilter::class;
    }
}
