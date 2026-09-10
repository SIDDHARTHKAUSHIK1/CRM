<?php

namespace Crm\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Crm\User\Contracts\User as UserContract;

class User extends Authenticatable implements UserContract
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'password_plain',
        'api_token',
        'role_id',
        'status',
        'view_permission',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'password_plain',
        'api_token',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Get image url for the product image.
     */
    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get image url for the product image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['image_url'] = $this->image_url;

        return $array;
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(RoleProxy::modelClass());
    }

    /**
     * The groups that belong to the user.
     */
    public function groups()
    {
        return $this->belongsToMany(GroupProxy::modelClass(), 'user_groups');
    }

    /**
     * Checks if user has permission to perform certain action.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (! $this->role) {
            return false;
        }

        if ($this->role->permission_type === 'all') {
            return true;
        }

        $permissions = $this->role->permissions;

        while (is_string($permissions)) {
            $decoded = json_decode($permissions, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $permissions = $decoded;
            } else {
                break;
            }
        }

        if (! is_array($permissions)) {
            return false;
        }

        return in_array($permission, $permissions);
    }
}
