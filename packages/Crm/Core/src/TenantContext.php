<?php

namespace Crm\Core;

class TenantContext
{
    /**
     * Cached tenant ID for the current request/execution context.
     */
    protected static ?int $tenantId = null;

    /**
     * Whether tenant ID has been explicitly resolved or set.
     */
    protected static bool $resolved = false;

    /**
     * Get the current tenant ID.
     */
    public static function getTenantId(): ?int
    {
        if (static::$resolved) {
            return static::$tenantId;
        }

        if (auth()->guard('user')->check()) {
            $user = auth()->guard('user')->user();
            static::$tenantId = $user?->tenant_id ? (int) $user->tenant_id : null;
            static::$resolved = true;

            return static::$tenantId;
        }

        return null;
    }

    /**
     * Explicitly set the current tenant ID (e.g. inside queued jobs, public webhooks).
     */
    public static function setTenantId(?int $tenantId): void
    {
        static::$tenantId = $tenantId;
        static::$resolved = true;
    }

    /**
     * Reset the tenant context (e.g. between queued jobs).
     */
    public static function reset(): void
    {
        static::$tenantId = null;
        static::$resolved = false;
    }
}
