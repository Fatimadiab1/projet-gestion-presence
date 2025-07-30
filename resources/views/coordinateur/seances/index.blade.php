@extends('layouts.coordinateur')

@section('title', 'Liste des séances')

@vite(['resources/css/coordinateur/seance/seanceindex.css'])

@section('content')
    <h1 class="title-users"><i class="bi bi-easel2"></i> Liste des séances</h1>

    {{-- Messages --}}
    @if (session('success'))
        <div class="alert-success" role="alert">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-error" role="alert">{{ session('error') }}</div>
    @endif

    <a href="{{ route('coordinateur.seances.create') }}" class="btn-create" title="Ajouter une nouvelle séance">
        <i class="bi bi-plus-lg"></i> Ajouter une séance
    </a>


    <form method="GET" action="{{ route('coordinateur.seances.index') }}">
        <div class="form-filters">

            <div>
                <label for="classe_id">Classe :</label>
                <select name="classe_id" id="classe_id">
                    <option value="">-- Toutes --</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="matiere_id">Matière :</label>
                <select name="matiere_id" id="matiere_id">
                    <option value="">-- Toutes --</option>
                    @foreach ($matieres as $matiere)
                        <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                            {{ $matiere->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="statut_id">Statut :</label>
                <select name="statut_id" id="statut_id">
                    <option value="">-- Tous --</option>
                    @foreach ($statuts as $statut)
                        <option value="{{ $statut->id }}" {{ request('statut_id') == $statut->id ? 'selected' : '' }}>
                            {{ $statut->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date">Date :</label>
                <input type="date" name="date" value="{{ request('date') }}">
            </div>
        </div>

        <div class="filter-buttons">
            <button type="submit" class="btn-filter" title="Appliquer les filtres">
                Filtrer
            </button>
            <a href="{{ route('coordinateur.seances.index') }}" class="btn-reset" title="Réinitialiser les filtres">
                <i class="bi bi-arrow-clockwise"></i> Réinitialiser
            </a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="table-container">
        <table class="style-table" aria-label="Liste des séances">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Jour</th>
                    <th scope="col">Heure</th>
                    <th scope="col">Classe</th>
                    <th scope="col">Matière</th>
                    <th scope="col">Professeur</th>
                    <th scope="col">Type</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seances as $seance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                        <td>{{ $seance->jour_semaine }}</td>
                        <td>{{ $seance->heure_debut }} - {{ $seance->heure_fin }}</td>
                        <td>{{ $seance->classeAnnee->classe->nom ?? 'N/A' }}</td>
                        <td>{{ $seance->matiere->nom ?? 'N/A' }}</td>
                        <td>{{ $seance->professeur->user->nom ?? '' }} {{ $seance->professeur->user->prenom ?? '' }}</td>
                        <td>{{ $seance->typeCours->nom ?? 'N/A' }}</td>
                        <td>{{ $seance->statutSeance->nom ?? 'Non défini' }}</td>
                        <td class="table-actions">
                            @if($seance->statutSeance && $seance->statutSeance->nom === 'Annulée')
                                <form action="{{ route('coordinateur.seances.destroy', $seance->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Supprimer" onclick="return confirm('Supprimer cette séance annulée ?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('coordinateur.seances.edit', $seance->id) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('coordinateur.seances.annuler', $seance->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-delete" title="Annuler" onclick="return confirm('Annuler cette séance ?')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>

                                <a href="{{ route('coordinateur.seances.formulaire-report', $seance->id) }}" class="btn-edit" title="Reporter">
                                    <i class="bi bi-arrow-repeat"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">Aucune séance trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $seances->withQueryString()->links() }}
    </div>
@endsection
