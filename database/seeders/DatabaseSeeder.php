<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Muhammad Ahib Ibrilli',
            'email' => 'brilliahib21@gmail.com',
            'password' => Hash::make('@Miftah12345'),
        ]);
    }
}
