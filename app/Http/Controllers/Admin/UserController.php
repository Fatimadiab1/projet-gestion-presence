<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// Modèles
use App\Models\User;
use App\Models\Role;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\ParentModel;
use App\Models\Coordinateur;

class UserController extends Controller
{
    // Afficher la liste des users
    public function index(Request $request)
    {
        $filtreRole = $request->input('role');

        $limiteParPage = $request->input('entries', 10);

        $requete = User::with('role')->orderBy('created_at', 'desc');

        if (!empty($filtreRole)) {
            $requete->whereHas('role', function ($filtration) use ($filtreRole) {
                $filtration->where('nom', $filtreRole);
            });
        }

        $utilisateurs = $requete->paginate($limiteParPage);

        $roles = Role::where('nom', '!=', 'admin')->get();

        return view('admin.users.index', [
            'utilisateurs' => $utilisateurs,
            'roles' => $roles,
            'filtreRole' => $filtreRole,
            'limiteParPage' => $limiteParPage
        ]);
    }

    // Creation du user
    public function create()
    {
        $roles = Role::where('nom', '!=', 'admin')->get();
        return view('admin.users.create', compact('roles'));
    }

    // Enregistrer un user
    public function store(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->email_prefix)) . '@ifran.ci'
        ]);

        $request->validate([
            'nom' => 'required|string|min:2|max:50',
            'prenom' => 'required|string|min:2|max:50',
            'email_prefix' => 'required|string',
            'email' => 'required|string|ends_with:@ifran.ci|unique:users,email',
            'mot_de_passe' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Gestion de l'upload de la photo 
        $cheminPhoto = null;
        if ($request->hasFile('photo')) {
            $cheminPhoto = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->mot_de_passe),
            'role_id' => $request->role_id,
            'photo' => $cheminPhoto,
        ]);

        $this->creerProfilPour($user);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    // Modifier un user
    public function edit(User $user)
    {
        $roles = Role::where('nom', '!=', 'admin')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Mise a jour du user
    public function update(Request $request, User $user)
    {
        $request->merge([
            'email' => strtolower(trim($request->email_prefix)) . '@ifran.ci'
        ]);

        // Validation
        $request->validate([
            'nom' => 'required|string|min:2|max:50',
            'prenom' => 'required|string|min:2|max:50',
            'email_prefix' => 'required|string',
            'email' => 'required|string|ends_with:@ifran.ci|unique:users,email,' . $user->id,
            'mot_de_passe' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('photos', 'public');
        }


        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;


        if ($request->filled('mot_de_passe')) {
            $user->password = Hash::make($request->mot_de_passe);
        }


        if ($user->role_id != $request->role_id) {
            $this->supprimerAncienProfil($user);
            $user->role_id = $request->role_id;
        }

        $user->save();

        $this->creerProfilPour($user);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    // Supprimer un user
    public function destroy(User $user)
    {

        $this->supprimerAncienProfil($user);


        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('error', 'Utilisateur supprimé.');
    }

    private function creerProfilPour(User $user)
    {
        $role = $user->role->nom;

        if ($role === 'etudiant') {
            Etudiant::firstOrCreate(['user_id' => $user->id]);
        } elseif ($role === 'professeur') {
            Professeur::firstOrCreate(['user_id' => $user->id]);
        } elseif ($role === 'parent') {
            ParentModel::firstOrCreate(['user_id' => $user->id]);
        } elseif ($role === 'coordinateur') {
            Coordinateur::firstOrCreate(['user_id' => $user->id]);
        }
    }

    private function supprimerAncienProfil(User $user)
    {
        Etudiant::where('user_id', $user->id)->delete();
        Professeur::where('user_id', $user->id)->delete();
        ParentModel::where('user_id', $user->id)->delete();
        Coordinateur::where('user_id', $user->id)->delete();
    }
}
