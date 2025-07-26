@extends('layouts.coordinateur')

@section('title', 'Liste des séances')

@vite(['resources/css/coordinateur/seance/seanceindex.css'])

@section('content')
    <h2 class="title-users">Liste des séances</h2>

    {{-- Message--}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('coordinateur.seances.create') }}" class="btn-create">
     + Ajouter une séance
    </a>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('coordinateur.seances.index') }}">
        <div class="form-filters">
            <label>Classe :</label>
            <select name="classe_id">
                <option value="">Toutes</option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->classe->nom }} - {{ $classe->anneeAcademique->annee }}
                    </option>
                @endforeach
            </select>

            <label>Matière :</label>
            <select name="matiere_id">
                <option value="">Toutes</option>
                @foreach ($matieres as $matiere)
                    <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                        {{ $matiere->nom }}
                    </option>
                @endforeach
            </select>

            <label>Trimestre :</label>
            <select name="trimestre_id">
                <option value="">Tous</option>
                @foreach ($trimestres as $trimestre)
                    <option value="{{ $trimestre->id }}" {{ request('trimestre_id') == $trimestre->id ? 'selected' : '' }}>
                        {{ $trimestre->nom }}
                    </option>
                @endforeach
            </select>

            <label>Du :</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}">

            <label>Au :</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}">

            <button type="submit">Filtrer</button>
            <a href="{{ route('coordinateur.seances.index') }}">Réinitialiser</a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="table-container">
        <table class="style-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Jour</th>
                    <th>Heure</th>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Professeur</th>
                    <th>Type</th>
                    <th>Trimestre</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seances as $seance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($seance->jour_semaine) }}</td>
                        <td>{{ $seance->heure_debut }} - {{ $seance->heure_fin }}</td>
                        <td>{{ $seance->classeAnnee->classe->nom ?? '-' }}</td>
                        <td>{{ $seance->matiere->nom ?? '-' }}</td>
                        <td>{{ $seance->professeur->user->prenom }} {{ $seance->professeur->user->nom }}</td>
                        <td>{{ $seance->typeCours->nom }}</td>
                        <td>{{ $seance->trimestre->nom }}</td>
                        <td>{{ $seance->statut->nom }}</td>
                        <td class="table-actions">
                            <a href="{{ route('coordinateur.seances.edit', $seance->id) }}" class="btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('coordinateur.seances.destroy', $seance->id) }}" method="POST" onsubmit="return confirm('Supprimer cette séance ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10">Aucune séance trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
