<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminExists = User::where('email', 'admin@voltronix.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Voltronix Admin',
                'email' => 'admin@voltronix.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@voltronix.com');
            $this->command->info('Password: password');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
