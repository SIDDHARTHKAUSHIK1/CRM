<?php

namespace Crm\WhatsApp\Repositories;

use Crm\Core\Eloquent\Repository;
use Crm\WhatsApp\Contracts\WhatsappCampaign;

class WhatsappCampaignRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'name',
        'caption',
        'status',
    ];

    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return WhatsappCampaign::class;
    }
}
