<?php

namespace Crm\WhatsApp\Models;

use Crm\Core\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Model;
use Crm\WhatsApp\Contracts\WhatsappCampaignRecipient as WhatsappCampaignRecipientContract;

class WhatsappCampaignRecipient extends Model implements WhatsappCampaignRecipientContract
{
    use BelongsToTenant;

    protected $table = 'whatsapp_campaign_recipients';

    protected $fillable = [
        'tenant_id',
        'whatsapp_campaign_id',
        'raw_input',
        'phone_e164',
        'status',
        'error_message',
        'sent_at',
        'attempts',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'sent_at' => 'datetime',
    ];

    /**
     * Parent campaign.
     */
    public function campaign()
    {
        return $this->belongsTo(WhatsappCampaignProxy::modelClass(), 'whatsapp_campaign_id');
    }
}
