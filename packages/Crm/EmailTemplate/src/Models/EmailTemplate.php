<?php

namespace Crm\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\EmailTemplate\Contracts\EmailTemplate as EmailTemplateContract;

class EmailTemplate extends Model implements EmailTemplateContract
{
    protected $fillable = [
        'name',
        'subject',
        'content',
    ];
}
