@extends('layouts.admin')

@section('title', 'Liste des classes par année')
@section('header', 'Classes par année')

@section('content')
    @vite(['resources/css/admin/classe/classeindex.css'])

    <div class="classe-container">
        <h2>Liste des classes par année</h2>

        {{-- Message --}}
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- Filtres et pagination --}}
        <form method="GET" action="{{ route('admin.classes.index') }}" class="filter-form">
            <label>Année :</label>
            <select name="annee" onchange="this.form.submit()">
                <option value="">Toutes</option>
                @foreach($annees as $annee)
                    <option value="{{ $annee->id }}" {{ $annee_id == $annee->id ? 'selected' : '' }}>
                        {{ $annee->annee }}
                    </option>
                @endforeach
            </select>

            <label>Classe :</label>
            <select name="classe" onchange="this.form.submit()">
                <option value="">Toutes</option>
                @foreach($allClasses as $classe)
                    <option value="{{ $classe->id }}" {{ $classe_id == $classe->id ? 'selected' : '' }}>
                        {{ $classe->nom }}
                    </option>
                @endforeach
            </select>

            <label>Coordinateur :</label>
            <select name="coordinateur" onchange="this.form.submit()">
                <option value="">Tous</option>
                @foreach($coordinateurs as $coordinateur)
                    <option value="{{ $coordinateur->id }}" {{ $coord_id == $coordinateur->id ? 'selected' : '' }}>
                        {{ $coordinateur->user->nom }} {{ $coordinateur->user->prenom }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.classes.create') }}" class="btn-ajouter">
            <i class="bi bi-plus-lg"></i> Associer une classe à une année
        </a>

        {{-- Tableau --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Année</th>
                    <th>Coordinateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $classeAssociee)
                    <tr>
                        <td>{{ $classeAssociee->classe->nom }}</td>
                        <td>{{ $classeAssociee->anneeAcademique->annee }}</td>
                        <td>{{ $classeAssociee->coordinateur->user->nom }} {{ $classeAssociee->coordinateur->user->prenom }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.classes.edit', $classeAssociee->id) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.classes.destroy', $classeAssociee->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Supprimer">
                                        <i class="bi bi-trash" style="color: red"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucune donnée trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
