<?php

namespace Crm\Automation\Repositories;

use Crm\Automation\Contracts\Workflow;
use Crm\Core\Eloquent\Repository;

class WorkflowRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return Workflow::class;
    }
}
