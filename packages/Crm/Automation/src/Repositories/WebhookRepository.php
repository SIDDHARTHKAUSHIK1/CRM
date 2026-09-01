<?php

namespace Crm\Automation\Repositories;

use Crm\Automation\Contracts\Webhook;
use Crm\Core\Eloquent\Repository;

class WebhookRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return Webhook::class;
    }
}
