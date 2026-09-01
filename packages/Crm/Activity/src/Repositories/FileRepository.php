<?php

namespace Crm\Activity\Repositories;

use Crm\Activity\Contracts\File;
use Crm\Core\Eloquent\Repository;

class FileRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return File::class;
    }
}
