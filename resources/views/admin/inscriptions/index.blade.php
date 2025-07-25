@extends('layouts.admin')

@section('title', 'Liste des inscriptions')
@vite(['resources/css/admin/inscription/inscriptionindex.css'])

@section('content')
    <h2 class="title-users">Liste des inscriptions</h2>

    {{-- Message --}}
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('admin.inscriptions.index') }}">
      <div class="top-bare">
            <a href="{{ route('admin.inscriptions.create') }}" class="btn-create">
                <i class="bi bi-plus-lg"></i> Nouvelle inscription
            </a>
        </div>
        <div class="form-filters">
            <div>
                <label for="annee_id">Année académique :</label>
                <select name="annee_id" id="annee_id" onchange="this.form.submit()">
                    <option value="">Toutes</option>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee->id }}" {{ $annee->id == $annee_id ? 'selected' : '' }}>
                            {{ $annee->annee }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="classe_id">Classe :</label>
                <select name="classe_id" id="classe_id" onchange="this.form.submit()">
                    <option value="">Toutes</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $classe->id == $classe_id ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="table-container">
        @if ($inscriptions->isEmpty())
            <p class="no-data">Aucune inscription trouvée.</p>
        @else
            <table class="style-table">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Classe</th>
                        <th>Année</th>
                        <th>Date</th>
                        <th>Suivi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inscriptions as $inscription)
                        @php
                            $dernierSuivi = $inscription->suivis->sortByDesc('date_decision')->first();
                        @endphp
                        <tr>
                            <td>{{ $inscription->etudiant->user->nom }} {{ $inscription->etudiant->user->prenom }}</td>
                            <td>{{ $inscription->classeAnnee->classe->nom }}</td>
                            <td>{{ $inscription->classeAnnee->anneeAcademique->annee }}</td>
                            <td>{{ \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y') }}</td>
                            <td>
                                {{ $dernierSuivi && $dernierSuivi->statutSuivi
                                    ? $dernierSuivi->statutSuivi->nom
                                    : 'Non défini' }}
                            </td>
                            <td class="actions">
                                <a href="{{ route('admin.inscriptions.edit', $inscription) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
