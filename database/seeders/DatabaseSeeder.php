<?php

namespace Database\Seeders;

use App\Models\StatutPresence;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            StatutSeanceSeeder::class,
            StatutPresenceSeeder::class,
            TypeCoursSeeder::class,
            StatutSuiviSeeder::class,
        ]);
        
    }
}
