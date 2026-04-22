<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Enums\PlatformRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
                            {--email= : The email address of the user} 
                            {--role= : The platform role (from PlatformRole Enum)} 
                            {--pass=password : The password for the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user and assign a platform role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Verify PlatformRole Enum exists
        if (!class_exists(PlatformRole::class)) {
            $this->error('❌ PlatformRole Enum not found. Please ensure spatie-roles module is installed.');
            return 1;
        }

        // Resolve Role model dynamically for ULID support
        $roleModel = config('permission.models.role');
        $validRoles = array_column(PlatformRole::cases(), 'value');

        // 2. Collect Email
        $email = $this->option('email');
        if (!$email) {
            $email = text(
                label: 'What is the email address?',
                placeholder: 'e.g. dev@agency.com',
                required: true,
                validate: fn (string $value) => match (true) {
                    !filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address is invalid.',
                    User::where('email', $value)->exists() => 'A user with this email already exists.',
                    default => null,
                }
            );
        }

        // 3. Collect Role
        $roleName = $this->option('role');
        if (!$roleName || !in_array($roleName, $validRoles)) {
            if ($roleName && !in_array($roleName, $validRoles)) {
                $this->warn("⚠️  Invalid role provided: $roleName");
            }

            $roleName = select(
                label: 'Which role should be assigned?',
                options: $validRoles,
                default: PlatformRole::PLATFORM_SUPER_ADMIN->value
            );
        }

        // 4. Collect Name & Password
        $name = text(label: 'Full Name', default: 'Platform User', required: true);
        $password = $this->option('pass');

        // 5. Create User
        $this->info("🚀 Creating user: $name ($email)...");
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // 6. Assign Role
        $role = $roleModel::where('name', $roleName)->first();
        if (!$role) {
            $this->warn("⚠️  Role '$roleName' exists in Enum but not in Database.");
            $this->info("💡 Run 'php artisan platform:create-roles' first.");
            $user->delete();
            return 1;
        }

        $user->assignRole($roleName);

        $this->info("✅ User created successfully!");
        $this->table(['Field', 'Value'], [
            ['ID', $user->id],
            ['Name', $user->name],
            ['Email', $user->email],
            ['Role', $roleName],
            ['Password', $password],
        ]);

        return 0;
    }
}
