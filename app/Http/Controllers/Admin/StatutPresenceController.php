<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatutPresence;

class StatutPresenceController extends Controller
{
    // Afficher la liste des statuts de présence
    public function index()
    {
        $statuts = StatutPresence::orderBy('id')->paginate(10);
        return view('admin.statuts-presence.index', compact('statuts'));
    }
}
