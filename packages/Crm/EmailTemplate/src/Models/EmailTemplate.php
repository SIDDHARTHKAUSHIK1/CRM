<?php

namespace Crm\EmailTemplate\Models;

use Crm\Core\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Model;
use Crm\EmailTemplate\Contracts\EmailTemplate as EmailTemplateContract;

class EmailTemplate extends Model implements EmailTemplateContract
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'subject',
        'content',
    ];
}
