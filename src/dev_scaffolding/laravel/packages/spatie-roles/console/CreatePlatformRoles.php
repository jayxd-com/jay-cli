<?php

namespace App\Console\Commands;

use App\Enums\PlatformRole;
use App\Enums\PlatformPermission;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CreatePlatformRoles extends Command
{
    protected $signature = 'platform:create-roles';
    protected $description = 'Create or sync all platform roles and permissions across all guards';

    public function handle(): void
    {
        $this->info('🔄 Resetting cached roles and permissions...');
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guards = array_keys(config('auth.guards', ['web' => []]));
        $this->info('🚀 Syncing across guards: ' . implode(', ', $guards));

        // 1. Create Permissions
        $this->info('🔑 Creating permissions...');
        foreach (PlatformPermission::cases() as $permission) {
            foreach ($guards as $guard) {
                Permission::firstOrCreate([
                    'name' => $permission->value,
                    'guard_name' => $guard
                ]);
            }
        }

        // 2. Create Roles and Assign Permissions
        $this->info('🎭 Creating roles and assigning permissions...');
        foreach (PlatformRole::cases() as $roleEnum) {
            foreach ($guards as $guard) {
                $role = Role::firstOrCreate([
                    'name' => $roleEnum->value,
                    'guard_name' => $guard
                ]);

                // Assign permissions based on role
                $permissions = $this->getPermissionsForRole($roleEnum);
                $role->syncPermissions($permissions);
            }
            $this->line("  ✅ Role Assigned: <info>{$roleEnum->value}</info>");
        }

        $this->info('🏁 All roles and permissions have been synced successfully.');
    }

    /**
     * Map permissions to roles based on agency-grade logic.
     */
    protected function getPermissionsForRole(PlatformRole $role): array
    {
        return match ($role) {
            PlatformRole::PLATFORM_SUPER_ADMIN => array_column(PlatformPermission::cases(), 'value'),

            PlatformRole::PLATFORM_ADMIN => [
                PlatformPermission::PLATFORM_VIEW_DASHBOARD->value,
                PlatformPermission::PLATFORM_VIEW_USERS->value,
                PlatformPermission::PLATFORM_MANAGE_USERS->value,
                PlatformPermission::PLATFORM_VIEW_MARKETING->value,
                PlatformPermission::PLATFORM_MANAGE_MARKETING->value,
                PlatformPermission::PLATFORM_VIEW_FINANCE->value,
                PlatformPermission::PLATFORM_VIEW_LOGS->value,
                PlatformPermission::PLATFORM_VIEW_USER_FINANCE->value,
                PlatformPermission::PLATFORM_VIEW_USER_MARKETING->value,
                PlatformPermission::PLATFORM_VIEW_USER_MEMBERS->value,
            ],

            PlatformRole::PLATFORM_ACCOUNTANT => [
                PlatformPermission::PLATFORM_VIEW_DASHBOARD->value,
                PlatformPermission::PLATFORM_VIEW_FINANCE->value,
                PlatformPermission::PLATFORM_MANAGE_FINANCE->value,
                PlatformPermission::PLATFORM_VIEW_USER_FINANCE->value,
            ],

            PlatformRole::PLATFORM_MARKETING => [
                PlatformPermission::PLATFORM_VIEW_DASHBOARD->value,
                PlatformPermission::PLATFORM_VIEW_MARKETING->value,
                PlatformPermission::PLATFORM_MANAGE_MARKETING->value,
                PlatformPermission::PLATFORM_VIEW_USER_MARKETING->value,
            ],

            PlatformRole::PLATFORM_ASSISTANT => [
                PlatformPermission::PLATFORM_VIEW_DASHBOARD->value,
                PlatformPermission::PLATFORM_VIEW_MARKETING->value,
                PlatformPermission::PLATFORM_VIEW_USER_MEMBERS->value,
            ],

            PlatformRole::USER_OWNER => [
                PlatformPermission::USER_VIEW_DASHBOARD->value,
                PlatformPermission::USER_MANAGE_PROFILE->value,
                PlatformPermission::USER_VIEW_MEMBERS->value,
                PlatformPermission::USER_MANAGE_MEMBERS->value,
                PlatformPermission::USER_VIEW_MARKETING->value,
                PlatformPermission::USER_MANAGE_MARKETING->value,
                PlatformPermission::USER_VIEW_FINANCE->value,
                PlatformPermission::USER_MANAGE_FINANCE->value,
                PlatformPermission::USER_VIEW_SUPPORT->value,
                PlatformPermission::USER_MANAGE_SUPPORT->value,
            ],

            PlatformRole::USER_ADMIN => [
                PlatformPermission::USER_VIEW_DASHBOARD->value,
                PlatformPermission::USER_VIEW_MEMBERS->value,
                PlatformPermission::USER_MANAGE_MEMBERS->value,
                PlatformPermission::USER_VIEW_MARKETING->value,
                PlatformPermission::USER_MANAGE_MARKETING->value,
                PlatformPermission::USER_VIEW_SUPPORT->value,
                PlatformPermission::USER_MANAGE_SUPPORT->value,
            ],

            PlatformRole::USER_ACCOUNTANT => [
                PlatformPermission::USER_VIEW_DASHBOARD->value,
                PlatformPermission::USER_VIEW_FINANCE->value,
                PlatformPermission::USER_MANAGE_FINANCE->value,
            ],

            PlatformRole::USER_MARKETING => [
                PlatformPermission::USER_VIEW_DASHBOARD->value,
                PlatformPermission::USER_VIEW_MARKETING->value,
                PlatformPermission::USER_MANAGE_MARKETING->value,
            ],

            PlatformRole::USER_ASSISTANT => [
                PlatformPermission::USER_VIEW_DASHBOARD->value,
                PlatformPermission::USER_VIEW_SUPPORT->value,
                PlatformPermission::USER_MANAGE_SUPPORT->value,
            ],

            default => [
                PlatformPermission::USER_VIEW_DASHBOARD->value,
            ],
        };
    }
}