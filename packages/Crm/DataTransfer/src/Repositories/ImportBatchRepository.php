<?php

namespace Crm\DataTransfer\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\DataTransfer\Contracts\ImportBatch;

class ImportBatchRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ImportBatch::class;
    }
}
