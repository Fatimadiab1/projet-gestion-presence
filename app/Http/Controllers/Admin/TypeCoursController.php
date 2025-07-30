<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeCours;

class TypeCoursController extends Controller
{
    // Afficher la liste des types de cours
    public function index()
    {
        $typesCours = TypeCours::orderBy('id')->paginate(10);
        return view('admin.types-cours.index', compact('typesCours'));
    }
}
