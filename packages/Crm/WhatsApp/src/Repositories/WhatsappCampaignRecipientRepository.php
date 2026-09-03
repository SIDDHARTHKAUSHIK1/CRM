<?php

namespace Crm\WhatsApp\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\WhatsApp\Contracts\WhatsappCampaignRecipient;

class WhatsappCampaignRecipientRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'phone_e164',
        'status',
    ];

    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return WhatsappCampaignRecipient::class;
    }
}
