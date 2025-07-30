@extends('layouts.admin')

@section('title', 'Classes par année')
@section('header', 'Liste des classes associées')

@vite(['resources/css/admin/classe/classeindex.css'])

@section('content')
<div class="classe-container">
    <h1><i class="bi bi-easel-fill"></i> Liste des classes par année</h1>

    {{-- Message --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.classes.index') }}" class="filter-form">
        <label for="annee">Année :</label>
        <select name="annee" id="annee" onchange="this.form.submit()">
            <option value="">Toutes</option>
            @foreach($annees as $annee)
                <option value="{{ $annee->id }}" {{ $annee_id == $annee->id ? 'selected' : '' }}>
                    {{ $annee->annee }}
                </option>
            @endforeach
        </select>

        <label for="classe">Classe :</label>
        <select name="classe" id="classe" onchange="this.form.submit()">
            <option value="">Toutes</option>
            @foreach($allClasses as $classe)
                <option value="{{ $classe->id }}" {{ $classe_id == $classe->id ? 'selected' : '' }}>
                    {{ $classe->nom }}
                </option>
            @endforeach
        </select>

        <label for="coordinateur">Coordinateur :</label>
        <select name="coordinateur" id="coordinateur" onchange="this.form.submit()">
            <option value="">Tous</option>
            @foreach($coordinateurs as $coord)
                <option value="{{ $coord->id }}" {{ $coord_id == $coord->id ? 'selected' : '' }}>
                    {{ $coord->user->nom }} {{ $coord->user->prenom }}
                </option>
            @endforeach
        </select>
    </form>

    <a href="{{ route('admin.classes.create') }}" class="btn-ajouter">
        <i class="bi bi-plus-lg"></i> Associer une classe
    </a>

    {{-- Tableau --}}
    <div class="table-wrapper">
        <table class="style-table">
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
                        <td class="table-actions">
                            <a href="{{ route('admin.classes.edit', $classeAssociee->id) }}" class="btn-edit" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('admin.classes.destroy', $classeAssociee->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-message">Aucune association trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
