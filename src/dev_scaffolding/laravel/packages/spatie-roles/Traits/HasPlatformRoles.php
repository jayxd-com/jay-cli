<?php

namespace App\Traits;

use App\Enums\PlatformRole;

trait HasPlatformRoles
{
    /**
     * Check if the user is a Platform Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(PlatformRole::PLATFORM_SUPER_ADMIN->value);
    }

    /**
     * Check if the user has administrative platform access.
     */
    public function isPlatformAdmin(): bool
    {
        return $this->hasAnyRole([
            PlatformRole::PLATFORM_SUPER_ADMIN->value,
            PlatformRole::PLATFORM_ADMIN->value,
        ]);
    }

    /**
     * Check if the user is a regular platform member.
     */
    public function isMember(): bool
    {
        return $this->hasRole(PlatformRole::PLATFORM_MEMBER->value);
    }
}
