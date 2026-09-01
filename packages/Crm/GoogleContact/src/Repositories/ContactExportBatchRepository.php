<?php

namespace Crm\GoogleContact\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\GoogleContact\Contracts\ContactExportBatch;

class ContactExportBatchRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ContactExportBatch::class;
    }
}
