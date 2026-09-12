<?php

namespace Crm\User\Models;

use Crm\Core\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Model;
use Crm\User\Contracts\Role as RoleContract;

class Role extends Model implements RoleContract
{
    use BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'permission_type',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Accessor for permissions attribute ensuring it always returns an array or null.
     */
    public function getPermissionsAttribute($permissions)
    {
        if (is_null($permissions)) {
            return null;
        }

        $decoded = is_string($permissions) ? json_decode($permissions, true) : $permissions;

        while (is_string($decoded)) {
            $temp = json_decode($decoded, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $decoded = $temp;
            } else {
                break;
            }
        }

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Mutator for permissions attribute avoiding double JSON encoding.
     */
    public function setPermissionsAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['permissions'] = null;
            return;
        }

        while (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                break;
            }
        }

        $this->attributes['permissions'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get the users.
     */
    public function users()
    {
        return $this->hasMany(UserProxy::modelClass());
    }
}
