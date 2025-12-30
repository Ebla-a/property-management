<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin'); // Spatie

        // Employees
        User::factory(4)->create()->each(function ($user) {
            $user->assignRole('employee');
        });

        // Customers
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('customer');
        });
    }
}
