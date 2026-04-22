<?php

namespace App\Enums;

enum PlatformPermission: string
{
    // --- PLATFORM SCOPE (Global) ---
    case PLATFORM_VIEW_DASHBOARD = 'platform.view_dashboard';
    case PLATFORM_MANAGE_SETTINGS = 'platform.manage_settings';
    case PLATFORM_MANAGE_ROLES = 'platform.manage_roles';
    
    // Platform Users
    case PLATFORM_VIEW_USERS = 'platform.view_users';
    case PLATFORM_MANAGE_USERS = 'platform.manage_users';

    // Platform Marketing
    case PLATFORM_VIEW_MARKETING = 'platform.view_marketing';
    case PLATFORM_MANAGE_MARKETING = 'platform.manage_marketing';
    
    // Platform Finance
    case PLATFORM_VIEW_FINANCE = 'platform.view_finance';
    case PLATFORM_MANAGE_FINANCE = 'platform.manage_finance';

    // Platform Logs/Audit
    case PLATFORM_VIEW_LOGS = 'platform.view_logs';
    case PLATFORM_CLEAN_LOGS = 'platform.clean_logs';

    // --- USER SCOPE (B2B / Company) ---
    case USER_VIEW_DASHBOARD = 'user.view_dashboard';
    case USER_MANAGE_PROFILE = 'user.manage_profile';
    
    // User Team/Members
    case USER_VIEW_MEMBERS = 'user.view_members';
    case USER_MANAGE_MEMBERS = 'user.manage_members';
    
    // User Marketing
    case USER_VIEW_MARKETING = 'user.view_marketing';
    case USER_MANAGE_MARKETING = 'user.manage_marketing';
    
    // User Finance
    case USER_VIEW_FINANCE = 'user.view_finance';
    case USER_MANAGE_FINANCE = 'user.manage_finance';
    
    // User Support
    case USER_VIEW_SUPPORT = 'user.view_support';
    case USER_MANAGE_SUPPORT = 'user.manage_support';

    // --- CROSS-SCOPE PERMISSIONS ---
    // (Ability for Platform roles to see User data)
    case PLATFORM_VIEW_USER_FINANCE = 'platform.view_user_finance';
    case PLATFORM_VIEW_USER_MARKETING = 'platform.view_user_marketing';
    case PLATFORM_VIEW_USER_MEMBERS = 'platform.view_user_members';
}
