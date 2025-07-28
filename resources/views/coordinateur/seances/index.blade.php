@extends('layouts.coordinateur')

@section('title', 'Liste des séances')

@vite(['resources/css/coordinateur/seance/seanceindex.css'])

@section('content')
    <h2 class="title-users">Liste des séances</h2>

    {{-- Message --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Message --}}
    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

  
    <a href="{{ route('coordinateur.seances.create') }}" class="btn-create">
        <i class="bi bi-plus-lg"></i> Ajouter une séance
    </a>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('coordinateur.seances.index') }}">
        <div class="form-filters">
            {{-- Filtre Classe --}}
            <div>
                <label for="classe_id">Classe :</label>
                <select name="classe_id" id="classe_id">
                    <option value="">-- Toutes --</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre Matière --}}
            <div>
                <label for="matiere_id">Matière :</label>
                <select name="matiere_id" id="matiere_id">
                    <option value="">-- Toutes --</option>
                    @foreach ($matieres as $m)
                        <option value="{{ $m->id }}" {{ request('matiere_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre Statut --}}
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

            {{-- Filtre Date --}}
            <div>
                <label for="date">Date :</label>
                <input type="date" name="date" value="{{ request('date') }}">
            </div>
        </div>

        <div class="filter-buttons">
            <button type="submit" class="btn-filter">
                <i class="bi bi-funnel-fill"></i> Filtrer
            </button>

            <a href="{{ route('coordinateur.seances.index') }}" class="btn-reset">
                <i class="bi bi-arrow-clockwise"></i> Réinitialiser
            </a>
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
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seances as $s)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                        <td>{{ $s->jour_semaine }}</td>
                        <td>{{ $s->heure_debut }} - {{ $s->heure_fin }}</td>
                        <td>{{ $s->classeAnnee->classe->nom ?? 'N/A' }}</td>
                        <td>{{ $s->matiere->nom ?? 'N/A' }}</td>
                        <td>{{ $s->professeur->user->nom ?? '' }} {{ $s->professeur->user->prenom ?? '' }}</td>
                        <td>{{ $s->typeCours->nom ?? 'N/A' }}</td>
                        <td>{{ $s->statutSeance->nom ?? 'Non défini' }}</td>
                        <td class="table-actions">
                      
                            @if($s->statutSeance && $s->statutSeance->nom === 'Annulée')
                                <form action="{{ route('coordinateur.seances.destroy', $s->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Supprimer cette séance annulée ?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @else
                           
                                <a href="{{ route('coordinateur.seances.edit', $s->id) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                        
                                <form action="{{ route('coordinateur.seances.annuler', $s->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Annuler cette séance ?')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>

                       
                                <a href="{{ route('coordinateur.seances.formulaire-report', $s->id) }}" class="btn-edit" title="Reporter">
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
@endsection
