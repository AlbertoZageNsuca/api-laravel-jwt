<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@admin.com'],
        [
            'name' => 'Alberto Admin',
            'email'=> 'admin@admin.com',
            'password'=> Hash::make('senha123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
