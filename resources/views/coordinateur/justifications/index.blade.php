@extends('layouts.coordinateur')

@section('title', 'Liste des absences')
@vite(['resources/css/coordinateur/justification/justificationindex.css'])

@section('content')
    <h1 class="title-users">Liste des absences</h1>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('coordinateur.justifications.index') }}" class="form-filters">
        <label for="justifie">Filtrer :</label>
        <select name="justifie" id="justifie" onchange="this.form.submit()">
            <option value="">-- Toutes --</option>
            <option value="oui" {{ request('justifie') == 'oui' ? 'selected' : '' }}>Justifiées</option>
            <option value="non" {{ request('justifie') == 'non' ? 'selected' : '' }}>Non justifiées</option>
        </select>
    </form>

    {{-- Tableau --}}
    <table class="style-table">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Matière</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $presence)
                <tr>
                    <td>{{ $presence->etudiant->user->prenom }} {{ $presence->etudiant->user->nom }}</td>
                    <td>{{ $presence->seance->matiere->nom }}</td>
                    <td>{{ \Carbon\Carbon::parse($presence->seance->date)->format('d/m/Y') }}</td>
                    <td class="{{ $presence->justification ? 'statut-vert' : 'statut-rouge' }}">
                        {{ $presence->justification ? 'Justifiée' : 'Non justifiée' }}
                    </td>
                    <td class="table-actions">
                        @if($presence->justification)
                            <a href="{{ route('coordinateur.justifications.edit', $presence->justification->id) }}" class="btn-warning">Modifier</a>
                        @else
                            <a href="{{ route('coordinateur.justifications.create', $presence->id) }}" class="btn-success">Justifier</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune absence trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="pagination-links">
    {{ $absences->links('pagination::bootstrap-5') }}
    </div>
@endsection
