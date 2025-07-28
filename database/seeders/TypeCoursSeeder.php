<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeCours;

class TypeCoursSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Présentiel', 'Workshop', 'E-learning'];

        foreach ($types as $nom) {
            TypeCours::firstOrCreate(['nom' => $nom]);
        }
    }
}
