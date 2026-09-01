<?php

namespace Crm\Marketing\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\Marketing\Contracts\Event;

class EventRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return Event::class;
    }
}
