@extends('layouts.admin')

@section('title', 'Liste des utilisateurs')
@vite(['resources/css/admin/user/userindex.css'])
@section('content')

    <h2 class="title-users">Liste des utilisateurs</h2>

   @if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert-success">{{ session('error') }}</div>
@endif


    {{-- Filtre et pagination --}}
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn-create">
                <i class="bi bi-plus-lg"></i> Créer un utilisateur
            </a>
        </div>

        <div class="form-filters">
            <label for="role">Filtrer par rôle :</label>
            <select name="role" id="role" onchange="this.form.submit()">
                <option value="">Tous</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->nom }}" {{ $filtreRole === $role->nom ? 'selected' : '' }}>
                        {{ ucfirst($role->nom) }}
                    </option>
                @endforeach
            </select>
            <label for="classe_annee_id">Filtrer par classe :</label>
<select name="classe_annee_id" id="classe_annee_id" onchange="this.form.submit()">
    <option value="">Toutes</option>
    @foreach ($classeAnnees as $ca)
        <option value="{{ $ca->id }}" {{ request('classe_annee_id') == $ca->id ? 'selected' : '' }}>
            {{ $ca->classe->nom }} - {{ $ca->anneeAcademique->annee }}
        </option>
    @endforeach
</select>


            <label for="entries">Nombre à afficher :</label>
            <input type="number" name="entries" id="entries" min="1" value="{{ $limiteParPage }}"
                onchange="this.form.submit()">
        </div>
    </form>

    {{-- tableau --}}
    <div class="table-container">
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
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo" class="img">
                            @else
                                <i class="bi bi-person-circle"></i>
                            @endif
                        </td>
                        <td>{{ $user->nom }}</td>
                        <td>{{ $user->prenom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($user->role->nom) }}</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                @if ($user->role->nom !== 'admin')
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
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
