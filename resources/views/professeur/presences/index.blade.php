@extends('layouts.professeur')

@section('title', 'Mes présences')

@vite(['resources/css/professeur/presence/index.css'])

@section('content')
    <h1 class="title-users"><i class="fas fa-calendar-check"></i> Mes séances </h1>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('professeur.presences.index') }}">
        <div class="form-filters">
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

            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filtrer
            </button>

            <a href="{{ route('professeur.presences.index') }}" class="btn-reset">
                <i class="fas fa-sync-alt"></i> Réinitialiser
            </a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="table-container">
        <table class="style-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Actions</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seances as $seance)
                    @php
                        $limite = \Carbon\Carbon::parse($seance->date)->addDays(14);
                        $editable = now()->lessThanOrEqualTo($limite);
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                        <td>{{ $seance->heure_debut }} - {{ $seance->heure_fin }}</td>
                        <td>{{ $seance->classeAnnee->classe->nom ?? '-' }}</td>
                        <td>{{ $seance->matiere->nom ?? '-' }}</td>
                        <td class="table-actions">
                            @if ($editable)
                                <a href="{{ route('professeur.presences.edit', $seance->id) }}" class="btn-action">
                                Gérer
                                </a>
                            @else
                                <span class="btn-disabled">
                                    <i class="fas fa-clock"></i> Expirée
                                </span>
                            @endif

                            <a href="{{ route('professeur.presences.show', $seance->id) }}" class="btn-secondary">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                        <td>
                            @if ($seance->presences_count > 0)
                                <span style="background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 5px; font-weight: bold;">
                                    Fiche remplie
                                </span>
                            @else
                                <span style="background-color: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 5px;">
                                    Non remplie
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Aucune séance trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
