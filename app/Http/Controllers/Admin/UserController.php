<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\ParentModel;
use App\Models\Coordinateur;
use App\Models\ClasseAnnee;

class UserController extends Controller
{
    // Liste des utilisateurs 
    public function index(Request $request)
    {
        $filtreRole = $request->input('role');
        $classeAnneeId = $request->input('classe_annee_id');
        $limiteParPage = $request->input('entries', 10);

        $utilisateurs = User::with('role')
            ->orderBy('created_at', 'desc');

        // Filtrer roles
        if ($filtreRole) {
            $utilisateurs->whereHas('role', function ($query) use ($filtreRole) {
                $query->where('nom', $filtreRole);
            });

            // Filtrer étudiants
            if ($classeAnneeId && $filtreRole === 'etudiant') {
                $utilisateurs->whereHas('etudiant.inscriptions', function ($query) use ($classeAnneeId) {
                    $query->where('classe_annee_id', $classeAnneeId);
                });
            }

            // Filtrer coordinateurs
            if ($classeAnneeId && $filtreRole === 'coordinateur') {
                $utilisateurs->whereHas('coordinateur.classeAnnees', function ($query) use ($classeAnneeId) {
                    $query->where('id', $classeAnneeId);
                });
            }
        }

        $utilisateurs = $utilisateurs->paginate($limiteParPage);
        $roles = Role::where('nom', '!=', 'admin')->get();
        $classeAnnees = ClasseAnnee::with(['classe', 'anneeAcademique'])->get();

        return view('admin.users.index', compact('utilisateurs', 'roles', 'classeAnnees', 'filtreRole', 'limiteParPage'));
    }

    // Créer un nouvel utilisateur
    public function create()
    {
        $roles = Role::where('nom', '!=', 'admin')->get();
        return view('admin.users.create', compact('roles'));
    }

    // Enregistrer un utilisateur
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

        // Upload photo 
        $cheminPhoto = null;
        if ($request->hasFile('photo')) {
            $cheminPhoto = $request->file('photo')->store('photos', 'public');
        }


        $utilisateur = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->mot_de_passe),
            'role_id' => $request->role_id,
            'photo' => $cheminPhoto,
        ]);


        $this->creerProfil($utilisateur);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    // Modifier un utilisateur
    public function edit(User $user)
    {
        $roles = Role::where('nom', '!=', 'admin')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, User $user)
    {
        $request->merge([
            'email' => strtolower(trim($request->email_prefix)) . '@ifran.ci'
        ]);

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

        // Mise à jour des champs
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;

        // Mise à jour du mot de passe 
        if ($request->filled('mot_de_passe')) {
            $user->password = Hash::make($request->mot_de_passe);
        }


        if ($user->role_id != $request->role_id) {
            $this->supprimerProfil($user);
            $user->role_id = $request->role_id;
        }

        $user->save();
        $this->creerProfil($user);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    // Supprimer un utilisateur
    public function destroy(User $user)
    {
        $this->supprimerProfil($user);

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('error', 'Utilisateur supprimé.');
    }



    // Crée le bon profil *
    private function creerProfil(User $user)
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

    // Supprime tous les profils 
    private function supprimerProfil(User $user)
    {
        Etudiant::where('user_id', $user->id)->delete();
        Professeur::where('user_id', $user->id)->delete();
        ParentModel::where('user_id', $user->id)->delete();
        Coordinateur::where('user_id', $user->id)->delete();
    }
}
