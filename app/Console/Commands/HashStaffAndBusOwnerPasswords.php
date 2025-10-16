<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashStaffAndBusOwnerPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hash-staff-and-bus-owner-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash passwords for staff and bus_owner users using bcrypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to hash passwords for staff and bus_owner users...');

        // Find users with staff and bus_owner roles that have plain text passwords
        $users = User::whereIn('role', ['staff', 'bus_owner'])
                    ->where(function($query) {
                        $query->where('password', 'not like', '$2y$%') // Not already bcrypt hashed
                              ->orWhere('password', 'not like', '$2a$%'); // Not already bcrypt hashed
                    })
                    ->get();

        if ($users->isEmpty()) {
            $this->info('No users found that need password hashing.');
            return;
        }

        $this->info("Found {$users->count()} users to process:");
        foreach ($users as $user) {
            $this->line("- ID: {$user->id}, Username: {$user->username}, Role: {$user->role}");
        }

        foreach ($users as $user) {
            // Hash the current password
            $hashedPassword = Hash::make($user->password);

            // Update the user with the hashed password
            $user->update([
                'password' => $hashedPassword
            ]);

            $this->line("✓ Hashed password for user: {$user->username} (ID: {$user->id})");
        }

        $this->info('All passwords have been successfully hashed!');
    }
}
