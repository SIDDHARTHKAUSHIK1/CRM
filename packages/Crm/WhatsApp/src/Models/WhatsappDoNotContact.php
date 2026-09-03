<?php

namespace Crm\WhatsApp\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\WhatsApp\Contracts\WhatsappDoNotContact as WhatsappDoNotContactContract;

class WhatsappDoNotContact extends Model implements WhatsappDoNotContactContract
{
    protected $table = 'whatsapp_do_not_contacts';

    protected $fillable = [
        'phone_e164',
        'reason',
    ];
}
