<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatutSeance;

class StatutSeanceSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = ['Prévue', 'Annulée', 'Reportée'];

        foreach ($statuts as $nom) {
            StatutSeance::firstOrCreate(['nom' => $nom]);
        }
    }
}

