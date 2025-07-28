@extends('layouts.coordinateur')

@section('title', 'Taux de présence par période')

@vite(['resources/css/coordinateur/calcul/calcul.css'])

@section('content')
    <h2 class="title-users">Taux de présence par étudiant sur une période</h2>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <form method="GET" class="form-filters">
        <label for="classe_id">Classe :</label>
        <select name="classe_id">
            <option value="">-- Choisir une classe --</option>
            @foreach ($classes as $classe)
                <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                    {{ $classe->classe->nom }}
                </option>
            @endforeach
        </select>

        <label for="date_debut">Du :</label>
        <input type="date" name="date_debut" value="{{ $debut }}">

        <label for="date_fin">Au :</label>
        <input type="date" name="date_fin" value="{{ $fin }}">

        <button type="submit">Afficher</button>
    </form>

    {{-- Résultats --}}
    @if(isset($resultats) && count($resultats) > 0)
     <table class="table-style">

            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Présences</th>
                    <th>Total séances</th>
                    <th>Taux de présence (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultats as $etudiant)
                    <tr>
                        <td>{{ $etudiant['nom'] }}</td>
                        <td>{{ $etudiant['prenom'] }}</td>
                        <td>{{ $etudiant['nb_presences'] }}</td>
                        <td>{{ $etudiant['nb_seances'] }}</td>
                        <td>{{ $etudiant['taux'] !== null ? $etudiant['taux'] . ' %' : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($classeId && $debut && $fin)
        <p class="no-data">Aucune donnée disponible pour cette période.</p>
    @endif
@endsection
