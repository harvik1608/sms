<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'sms@gmail.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'sms@gmail.com',
                'password' => Hash::make('sms@123')
            ]);
        }
    }
}
