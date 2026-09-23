<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetAdminCredentials extends Command
{
    protected $signature = 'admin:set-credentials {email? : New Super Admin login email (prompted if omitted)}';

    protected $description = 'Update the Super Admin login email and password (creates the account if none exists yet)';

    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('New Super Admin email');
        $password = $this->secret('New Super Admin password');

        if (! $password) {
            $this->error('Password cannot be empty.');

            return self::FAILURE;
        }

        $admin = User::first() ?? new User(['name' => 'Tiko Super Admin']);
        $admin->name = $admin->name ?: 'Tiko Super Admin';
        $admin->email = $email;
        $admin->password = $password;
        $admin->save();

        $this->info("Super Admin credentials updated for {$admin->email}.");

        return self::SUCCESS;
    }
}
