<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'test',
            'email' => 'akihisatengshan@gmail.com',
            'password' => Hash::make('password'),
            'postal_code' => '8191111',
            'address' => '765 Tomari, Itoshima City, Fukuoka Prefecture',
            'phone' => '08085913825',
            'email_verified_at' => now(),
        ]);
    }
}