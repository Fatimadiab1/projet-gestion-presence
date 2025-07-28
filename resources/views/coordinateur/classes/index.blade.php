@extends('layouts.coordinateur')

@section('title', 'Mes étudiants')
@vite(['resources/css/coordinateur/classe/index.css'])

@section('content')
    <h2 class="title-roles">Liste des étudiants</h2>

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

    {{-- Filtre --}}
    <form method="GET" action="{{ route('coordinateur.classes') }}">
        <div class="form-filters">
            <label for="classe_id">Classe :</label>
            <select name="classe_id" id="classe_id" onchange="this.form.submit()">
                <option value="">Toutes</option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->classe->nom }} - {{ $classe->anneeAcademique->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Liste --}}
    @if ($etudiants->isEmpty())
        <p class="text">Aucun étudiant trouvé.</p>
    @else
        <div class="table-container">
            <table class="style-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Classe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($etudiants as $inscription)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ strtoupper($inscription->etudiant->user->nom) }}</td>
                            <td>{{ ucfirst($inscription->etudiant->user->prenom) }}</td>
                            <td>{{ $inscription->etudiant->user->email }}</td>
                            <td>{{ $inscription->classeAnnee->classe->nom }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
