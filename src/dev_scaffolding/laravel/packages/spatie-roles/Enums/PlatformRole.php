<?php

namespace App\Enums;

enum PlatformRole: string
{
    case PLATFORM_SUPER_ADMIN = 'platform_superadmin';
    case PLATFORM_ADMIN = 'platform_admin';
    case PLATFORM_MANAGER = 'platform_manager';
    case PLATFORM_ACCOUNTANT = 'platform_accountant';
    case PLATFORM_MARKETING = 'platform_marketing';
    case PLATFORM_ASSISTANT = 'platform_assistant';
    case PLATFORM_MEMBER = 'platform_member';

    case USER_OWNER = 'user_owner';
    case USER_ADMIN = 'user_admin';
    case USER_ACCOUNTANT = 'user_accountant';
    case USER_MARKETING = 'user_marketing';
    case USER_ASSISTANT = 'user_assistant';
    case USER_MEMBER = 'user_member';

    case USER_SUBSCRIBER = 'user_subscriber';

    public function label(): string
    {
        return match ($this) {
            self::PLATFORM_SUPER_ADMIN => 'Platform Super Admin',
            self::PLATFORM_ADMIN => 'Platform Admin',
            self::PLATFORM_MANAGER => 'Platform Manager',
            self::PLATFORM_ACCOUNTANT => 'Platform Accountant',
            self::PLATFORM_MARKETING => 'Platform Marketing',
            self::PLATFORM_ASSISTANT => 'Platform Assistant',
            self::PLATFORM_MEMBER => 'Platform Member',
            self::USER_OWNER => 'User Owner',
            self::USER_ADMIN => 'User Admin',
            self::USER_ACCOUNTANT => 'User Accountant',
            self::USER_MARKETING => 'User Marketing',
            self::USER_ASSISTANT => 'User Assistant',
            self::USER_MEMBER => 'User Member',
            self::USER_SUBSCRIBER => 'User Subscriber',
        };
    }
}
