<?php

namespace Crm\Marketing\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\Marketing\Contracts\Campaign;

class CampaignRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return Campaign::class;
    }
}
