<?php

namespace Crm\Automation\Models;

use Crm\Core\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Model;
use Crm\Automation\Contracts\Workflow as WorkflowContract;

class Workflow extends Model implements WorkflowContract
{
    use BelongsToTenant;

    protected $casts = [
        'conditions' => 'array',
        'actions' => 'array',
    ];

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'entity_type',
        'event',
        'condition_type',
        'conditions',
        'actions',
    ];
}
