<?php

namespace Crm\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Crm\Attribute\Traits\CustomAttribute;
use Crm\Contact\Contracts\Organization as OrganizationContract;
use Crm\User\Models\UserProxy;

class Organization extends Model implements OrganizationContract
{
    use CustomAttribute;

    protected $casts = [
        'address' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'user_id',
    ];

    /**
     * Get persons.
     *
     * @return HasMany
     */
    public function persons()
    {
        return $this->hasMany(PersonProxy::modelClass());
    }

    /**
     * Get the user that owns the lead.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }
}
