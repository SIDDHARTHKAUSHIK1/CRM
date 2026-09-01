<?php

namespace Crm\GoogleContact\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\GoogleContact\Contracts\ContactExportBatchItem;

class ContactExportBatchItemRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ContactExportBatchItem::class;
    }
}
