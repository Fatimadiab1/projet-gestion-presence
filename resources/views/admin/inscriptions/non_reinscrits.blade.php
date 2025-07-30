@extends('layouts.admin')

@section('title', 'Étudiants non réinscrits')
@vite(['resources/css/admin/inscription/nonreinscrits.css'])

@section('content')
    <h1 class="titre-tableau">
        <i class="bi bi-person-x-fill"></i> Étudiants non réinscrits pour {{ $anneeActuelle->annee ?? 'année inconnue' }}
    </h1>

  
    <form method="GET" action="{{ route('admin.inscriptions.non_reinscrits') }}" class="form-filtres mb-6">
        <label for="annee_id" class="label-filtre">Année :</label>
        <select name="annee_id" id="annee_id" onchange="this.form.submit()" class="select-filtre">
            @foreach ($annees as $annee)
                <option value="{{ $annee->id }}" {{ $annee->id == $anneeActuelle->id ? 'selected' : '' }}>
                    {{ $annee->annee }}
                </option>
            @endforeach
        </select>

        <label for="classe_id" class="label-filtre">Classe :</label>
        <select name="classe_id" id="classe_id" onchange="this.form.submit()" class="select-filtre">
            <option value="">Toutes</option>
            @foreach ($classes as $classe)
                <option value="{{ $classe->id }}" {{ $classe_id == $classe->id ? 'selected' : '' }}>
                    {{ $classe->nom }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Tableau --}}
    <div class="tableau-container">
        @if ($nonReinscrits->isEmpty())
            <p>Aucun étudiant non réinscrit trouvé.</p>
        @else
            <table class="tableau-donnees">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nonReinscrits as $etudiant)
                        <tr>
                            <td>{{ $etudiant->user->nom }}</td>
                            <td>{{ $etudiant->user->prenom }}</td>
                            <td>
                                <a href="{{ route('admin.inscriptions.reinscrire', $etudiant->id) }}" class="lien-modifier">
                                    <i class="bi bi-arrow-repeat"></i> Réinscrire
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
