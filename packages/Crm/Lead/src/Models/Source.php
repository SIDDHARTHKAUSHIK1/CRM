<?php

namespace Crm\Lead\Models;

use Crm\Core\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Model;
use Crm\Lead\Contracts\Source as SourceContract;

class Source extends Model implements SourceContract
{
    use BelongsToTenant;

    protected $table = 'lead_sources';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
    ];

    /**
     * Get the leads.
     */
    public function leads()
    {
        return $this->hasMany(LeadProxy::modelClass(), 'lead_source_id', 'id');
    }
}
