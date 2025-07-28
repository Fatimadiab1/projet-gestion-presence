<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatutSuivi;

class StatutSuiviSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = [
            'Réussi',
            'Redoublé',
            'Exclu',
            'Repêché'
        ];

        foreach ($statuts as $nom) {
            StatutSuivi::firstOrCreate(['nom' => $nom]);
        }
    }
}
