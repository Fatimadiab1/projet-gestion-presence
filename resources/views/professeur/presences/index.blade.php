@extends('layouts.professeur')

@section('title', 'Mes présences')
@vite(['resources/css/professeur/presence/index.css'])

@section('content')
    <h2 class="title-users">Mes séances à gérer</h2>

    <form method="GET" class="form-filters">
        <label for="date">Date :</label>
        <input type="date" name="date" value="{{ request('date') }}">

        <label for="matiere_id">Matière :</label>
        <select name="matiere_id">
            <option value="">Toutes</option>
            @foreach ($matieres as $matiere)
                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                    {{ $matiere->nom }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filtrer</button>
     <a href="{{ route('professeur.presences.index') }}" class="reset-link">Réinitialiser</a>
    </form>

    @if ($seances->isEmpty())
        <p>Aucune séance trouvée.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seances as $seance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                        <td>{{ $seance->heure_debut }} - {{ $seance->heure_fin }}</td>
                        <td>{{ $seance->classeAnnee->classe->nom ?? '-' }}</td>
                        <td>{{ $seance->matiere->nom ?? '-' }}</td>
                        <td>
                            @php
                                $limite = \Carbon\Carbon::parse($seance->date)->addDays(14);
                                $editable = now()->lessThanOrEqualTo($limite);
                            @endphp

                            @if ($editable)
                                <a href="{{ route('professeur.presences.edit', $seance->id) }}" class="btn-action">Gérer la présence</a>
                            @else
                                <span class="btn-disabled">Modification expirée</span>
                            @endif

                            <a href="{{ route('professeur.presences.show', $seance->id) }}" class="btn-secondary">Voir la fiche</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
