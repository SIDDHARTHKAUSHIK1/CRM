<?php

namespace Crm\EmailTemplate\Repositories;

use Crm\Core\Eloquent\Repository;

class EmailTemplateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\EmailTemplate\Contracts\EmailTemplate';
    }
}
