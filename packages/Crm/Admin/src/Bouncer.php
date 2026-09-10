<?php

namespace Crm\Admin;

use Crm\User\Repositories\UserRepository;

class Bouncer
{
    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public function hasPermission($permission)
    {
        if (auth()->guard('user')->check() && auth()->guard('user')->user()->role->permission_type == 'all') {
            return true;
        } else {
            if (! auth()->guard('user')->check() || ! auth()->guard('user')->user()->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public static function allow($permission)
    {
        if (! auth()->guard('user')->check() || ! auth()->guard('user')->user()->hasPermission($permission)) {
            abort(401, 'This action is unauthorized');
        }
    }

    /**
     * This function will return user ids of current user's groups or self.
     * Only full administrators (permission_type === 'all') receive null (unrestricted global access).
     *
     * @return array|null
     */
    public function getAuthorizedUserIds()
    {
        $user = auth()->guard('user')->user();

        if (! $user) {
            return [];
        }

        // Full administrators have unrestricted global visibility across the entire CRM
        if ($user->role && $user->role->permission_type === 'all') {
            return null;
        }

        if ($user->view_permission === 'group') {
            return app(UserRepository::class)->getCurrentUserGroupsUserIds();
        }

        // Standard employees are strictly confined to their own user records
        return [$user->id];
    }
}
