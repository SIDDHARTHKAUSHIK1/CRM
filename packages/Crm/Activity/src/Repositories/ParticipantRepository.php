<?php

namespace Crm\Activity\Repositories;

use Crm\Core\Eloquent\Repository;

class ParticipantRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Crm\Activity\Contracts\Participant';
    }
}
