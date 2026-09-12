<?php

namespace Crm\WhatsApp\Models;

use Crm\Core\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Model;
use Crm\WhatsApp\Contracts\WhatsappDoNotContact as WhatsappDoNotContactContract;

class WhatsappDoNotContact extends Model implements WhatsappDoNotContactContract
{
    use BelongsToTenant;

    protected $table = 'whatsapp_do_not_contacts';

    protected $fillable = [
        'tenant_id',
        'phone_e164',
        'reason',
    ];
}
