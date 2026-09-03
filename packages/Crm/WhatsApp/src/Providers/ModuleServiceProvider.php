<?php

namespace Crm\WhatsApp\Providers;

use Crm\Core\Providers\BaseModuleServiceProvider;
use Crm\WhatsApp\Models\WhatsappCampaign;
use Crm\WhatsApp\Models\WhatsappCampaignRecipient;
use Crm\WhatsApp\Models\WhatsappDoNotContact;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        WhatsappCampaign::class,
        WhatsappCampaignRecipient::class,
        WhatsappDoNotContact::class,
    ];
}
