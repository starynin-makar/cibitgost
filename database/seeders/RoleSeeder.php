<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'Клиент', 'slug' => 'client']);
        Role::create(['name' => 'Аудитор', 'slug' => 'auditor']);
    }
} 