<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nom', 'admin')->first();

        User::firstOrCreate(
            [
                'email' => 'admin@ifran.com'
            ],
            [
                'nom'      => 'Admin',
                'prenom'   => 'Principal',
                'password' => Hash::make('Adminifran2020'),
                'role_id'  => $adminRole->id,
            ]
        );
    }
}
