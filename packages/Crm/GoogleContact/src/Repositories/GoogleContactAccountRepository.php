<?php

namespace Crm\GoogleContact\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\GoogleContact\Contracts\GoogleContactAccount;

class GoogleContactAccountRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return GoogleContactAccount::class;
    }
}
