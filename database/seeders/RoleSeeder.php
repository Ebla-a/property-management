<?php

namespace Database\Seeders;

 use Spatie\Permission\Models\Role;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
      public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin','guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'employee','guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'customer','guard_name' => 'web']);
    }
    }

