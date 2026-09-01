<?php

namespace Crm\DataTransfer\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\DataTransfer\Contracts\Import;

class ImportRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Import::class;
    }
}
