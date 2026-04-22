<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PlatformPermission;

class PlatformPolicy
{
    /**
     * --- PLATFORM LEVEL POLICIES ---
     */

    public function managePlatform(User $user): bool
    {
        return $user->can(PlatformPermission::PLATFORM_MANAGE_SETTINGS->value);
    }

    public function viewPlatformAnalytics(User $user): bool
    {
        return $user->can(PlatformPermission::PLATFORM_VIEW_MARKETING->value);
    }

    public function managePlatformMarketing(User $user): bool
    {
        return $user->can(PlatformPermission::PLATFORM_MANAGE_MARKETING->value);
    }

    public function managePlatformFinance(User $user): bool
    {
        return $user->can(PlatformPermission::PLATFORM_MANAGE_FINANCE->value);
    }

    /**
     * --- USER/COMPANY LEVEL POLICIES ---
     */

    public function manageUserMarketing(User $user): bool
    {
        return $user->can(PlatformPermission::USER_MANAGE_MARKETING->value);
    }

    public function manageUserFinance(User $user): bool
    {
        return $user->can(PlatformPermission::USER_MANAGE_FINANCE->value);
    }

    public function manageUserSupport(User $user): bool
    {
        return $user->can(PlatformPermission::USER_MANAGE_SUPPORT->value);
    }

    /**
     * --- CROSS-SCOPE POLICIES (Visibility) ---
     */

    public function viewUserMarketingFromPlatform(User $user): bool
    {
        return $user->can(PlatformPermission::PLATFORM_VIEW_USER_MARKETING->value);
    }

    public function viewUserFinanceFromPlatform(User $user): bool
    {
        return $user->can(PlatformPermission::PLATFORM_VIEW_USER_FINANCE->value);
    }
}
