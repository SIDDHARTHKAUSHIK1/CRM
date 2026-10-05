<?php

namespace Crm\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Crm\User\Models\User;

class Tenant extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
        'owner_user_id',
        'contact_email',
        'contact_phone',
        'signup_source',
        'signup_ip',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the owner user of the tenant.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * Get the users for the tenant.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
