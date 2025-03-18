<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@cafebisangopi.com',
                'password' => Hash::make('password'),
                'role_id' => 1
            ],
            [
                'name' => 'Manajer',
                'email' => 'manajer@cafebisangopi.com',
                'password' => Hash::make('password'),
                'role_id' => 2
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@cafebisangopi.com',
                'password' => Hash::make('password'),
                'role_id' => 3
            ],
        ]);
    }
}