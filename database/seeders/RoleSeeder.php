<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'etudiant', 'professeur', 'parent', 'coordinateur'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nom' => $role]);
        }
    }
}
