<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatutSuivi;

class StatutSuiviController extends Controller
{
    // Afficher la liste des statuts de suivi
    public function index()
    {
        $statuts = StatutSuivi::orderBy('id')->get(); 
        return view('admin.statuts-suivi.index', compact('statuts'));
    }
}
