<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        // 4 Employees
        User::factory(4)->create([
            'role_id' => Role::where('name', 'employee')->first()->id,
        ]);

        // 10 Customers
        User::factory(10)->create([
            'role_id' => Role::where('name', 'customer')->first()->id,
        ]);
    }
 }
