<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin321',
            'password' => Hash::make(12345678),
        ]);

        $admin->assignRole('admin');

        for($i = 1; $i <= 3; $i++){
            $user = User::create([
                'name' => 'User ' . $i,
                'username' => 'user' . $i,
                'password' => Hash::make(12345678),
            ]);
    
            $user->assignRole('user');
        }
    }
}
