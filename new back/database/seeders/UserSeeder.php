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
        if(!User::where('email','cesar@moraes.com.br')->first()){
            $superAdmin = User::create([
                'name' => 'Cesar',
                'email' => 'cesar@moraes.com.br',
                'password' => Hash::make('123456', ['rounds' => 12])
            ]);
        }

        if(!User::where('email','maria@moraes.com.br')->first()){
            $superAdmin = User::create([
                'name' => 'Maria',
                'email' => 'maria@moraes.com.br',
                'password' => Hash::make('123456', ['rounds' => 12])
            ]);
        }

        if(!User::where('email','rosa@moraes.com.br')->first()){
            $superAdmin = User::create([
                'name' => 'Rosa',
                'email' => 'rosa@moraes.com.br',
                'password' => Hash::make('123456', ['rounds' => 12])
            ]);
        }
    }
}
