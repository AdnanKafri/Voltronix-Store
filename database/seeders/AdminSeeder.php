<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Voltronix Admin',
            'email' => 'admin@voltronix.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Admin::create([
            'name' => 'Store Manager',
            'email' => 'manager@voltronix.com',
            'password' => Hash::make('manager123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
