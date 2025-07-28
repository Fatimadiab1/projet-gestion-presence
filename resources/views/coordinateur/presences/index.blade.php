@extends('layouts.coordinateur')

@section('title', 'Gestion des présences')

@vite(['resources/css/coordinateur/presence/presenceindex.css'])

@section('content')
    <h2 class="title-users">Gestion des présences</h2>

    {{-- ✅ Messages flash --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- ✅ Filtres --}}
    <form method="GET" action="{{ route('coordinateur.presences.index') }}">
        <div class="form-filters">

            {{-- Classe --}}
            <label for="classe_id">Classe :</label>
            <select name="classe_id" id="classe_id">
                <option value="">Toutes</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->classe->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Matière --}}
            <label for="matiere_id">Matière :</label>
            <select name="matiere_id" id="matiere_id">
                <option value="">Toutes</option>
                @foreach ($matieres as $m)
                    <option value="{{ $m->id }}" {{ request('matiere_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Date --}}
            <label for="date">Date :</label>
            <input type="date" name="date" value="{{ request('date') }}">

            <button type="submit" class="btn-filter">Filtrer</button>
        </div>
    </form>

    {{-- ✅ Tableau des séances --}}
    <table class="style-table">
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
            @forelse ($seances as $s)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                    <td>{{ $s->heure_debut }} - {{ $s->heure_fin }}</td>
                    <td>{{ $s->classeAnnee->classe->nom ?? '-' }}</td>
                    <td>{{ $s->matiere->nom ?? '-' }}</td>
                    <td class="table-actions">
                        @if ($s->statutSeance && $s->statutSeance->nom === 'Prévue')
                            <a href="{{ route('coordinateur.presences.edit', $s->id) }}" class="btn-action">
                                Gérer la présence
                            </a>
                        @else
                            <span class="btn-disabled">Non disponible</span>
                        @endif

                        {{-- Bouton Voir la fiche --}}
                        <a href="{{ route('coordinateur.presences.show', $s->id) }}" class="btn-secondary">
                            Voir la fiche
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune séance trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
