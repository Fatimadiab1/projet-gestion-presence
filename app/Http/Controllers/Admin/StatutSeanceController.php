<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatutSeance;

class StatutSeanceController extends Controller
{
    // Afficher la liste des statuts de séance
    public function index()
    {
        $statuts = StatutSeance::orderBy('id')->paginate(10);
        return view('admin.statuts-seance.index', compact('statuts'));
    }
}
