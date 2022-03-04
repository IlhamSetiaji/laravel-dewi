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
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'Admin',
                'email' => 'admin@test.test',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Siswa',
                'email' => 'siswa@test.test',
                'password' => Hash::make('password'),
            ],
        ])->each(function($users){
            $user = User::create($users);
            if($user->id == 1)
            {
                $user->assignRole('admin');
            }else{
                $user->assignRole('siswa');
            }
        });
    }
}
