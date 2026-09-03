<?php

namespace Crm\WhatsApp\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\WhatsApp\Contracts\WhatsappDoNotContact;

class WhatsappDoNotContactRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'phone_e164',
        'reason',
    ];

    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return WhatsappDoNotContact::class;
    }
}
