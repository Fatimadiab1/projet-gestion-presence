@extends('layouts.professeur')

@section('title', 'Mes séances')
@vite('resources/css/professeur/seance.css')

@section('content')
    <h1 class="title-users">Mes séances</h1>

    {{-- Formulaire de filtres --}}
    <form method="GET" class="form-filters">
        <label for="date">Date :</label>
        <input type="date" name="date" value="{{ request('date') }}">

        <label for="matiere_id">Matière :</label>
        <select name="matiere_id">
            <option value="">Toutes les matières</option>
            @foreach ($matieres as $matiere)
                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                    {{ $matiere->nom }}
                </option>
            @endforeach
        </select>

        <div class="filter-buttons">
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            <a href="{{ route('professeur.seances.index') }}" class="btn-reset">
                <i class="fas fa-undo"></i> Réinitialiser
            </a>
        </div>
    </form>

    {{-- Tableau --}}
    @if($seances->isEmpty())
        <p>Aucune séance trouvée.</p>
    @else
        <div class="table-container">
            <table class="style-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Classe</th>
                        <th>Matière</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seances as $seance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                            <td>{{ $seance->heure_debut }} - {{ $seance->heure_fin }}</td>
                            <td>{{ $seance->classeAnnee->classe->nom ?? '-' }}</td>
                            <td>{{ $seance->matiere->nom ?? '-' }}</td>
                            <td>{{ $seance->typeCours->nom ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <div class="pagination-box">
                        {{ $seances->links() }}
                    </div>
                </tbody>
            </table>
        </div>
    @endif
@endsection
