<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@wisuda.local'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'operator@wisuda.local'],
            ['name' => 'Operator Scanner', 'password' => Hash::make('password'), 'role' => 'operator']
        );

        User::updateOrCreate(
            ['email' => 'display@wisuda.local'],
            ['name' => 'Display TV', 'password' => Hash::make('password'), 'role' => 'display']
        );
    }
}
