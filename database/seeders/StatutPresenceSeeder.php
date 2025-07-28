<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatutPresence;

class StatutPresenceSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = ['Présent', 'Absent', 'En retard'];

        foreach ($statuts as $nom) {
            StatutPresence::firstOrCreate(['nom' => $nom]);
        }
    }
}
