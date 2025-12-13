<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'email' => 'admin@perpus.test',
    'password' => Hash::make('password'),
    'role' => 'admin'
]);

User::create([
    'name' => 'Mahasiswa',
    'email' => 'user@perpus.test',
    'password' => Hash::make('password'),
    'role' => 'mahasiswa'
]);
