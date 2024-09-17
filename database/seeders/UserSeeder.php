<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'hamza issa',
            'email' => 'hamza@gmail.com',
            'password' => Hash::make('hamza123@'),
            'role' => 'tester'
        ]);
        User::create([
            'name' => 'mhamd issa',
            'email' => 'mhamd@gmail.com',
            'password' => Hash::make('mhamd123@'),
            'role' => 'developer'
        ]);
        User::create([
            'name' => 'ahmad issa',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('ahmad123@'),
            'role' => 'manager'
        ]);
    }
}
