@extends('layouts.admin')

@section('title', 'Utilisateurs – IFRAN')

@vite(['resources/css/admin/user/userindex.css'])

@section('content')
    <h1 class="title-users">
        <i class="bi bi-people-fill"></i> Liste des utilisateurs
    </h1>

    {{-- Messages --}}
    @if (session('success'))
        <div class="alert-success" role="alert">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-error" role="alert">{{ session('error') }}</div>
    @endif

    {{-- Filtres et bouton --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar" aria-label="Filtres de recherche utilisateurs">
        <div class="form-filters">
            <label for="role">Rôle :</label>
            <select name="role" id="role" onchange="this.form.submit()">
                <option value="">Tous</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->nom }}" {{ $filtreRole === $role->nom ? 'selected' : '' }}>
                        {{ ucfirst($role->nom) }}
                    </option>
                @endforeach
            </select>

            <label for="classe_annee_id">Classe :</label>
            <select name="classe_annee_id" id="classe_annee_id" onchange="this.form.submit()">
                <option value="">Toutes</option>
                @foreach ($classeAnnees as $classe)
                    <option value="{{ $classe->id }}" {{ request('classe_annee_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->classe->nom }} – {{ $classe->anneeAcademique->annee }}
                    </option>
                @endforeach
            </select>

            <label for="entries">Afficher :</label>
            <input type="number" name="entries" id="entries" min="1" value="{{ $limiteParPage }}" onchange="this.form.submit()">
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn-create" title="Créer un nouvel utilisateur">
            <i class="bi bi-plus-lg"></i> Créer un utilisateur
        </a>
    </form>

    {{-- Tableau des utilisateurs --}}
    <div class="table-container" role="region" aria-label="Tableau des utilisateurs IFRAN">
        <table class="style-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($utilisateurs as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de {{ $user->prenom }}" class="img" />
                            @else
                                <i class="bi bi-person-circle fs-3 text-muted"></i>
                            @endif
                        </td>
                        <td>{{ $user->nom }}</td>
                        <td>{{ $user->prenom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-role {{ $user->role->nom }}">{{ ucfirst($user->role->nom) }}</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                @if ($user->role->nom !== 'admin')
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit" title="Modifier l'utilisateur">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Supprimer l'utilisateur">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">–</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
