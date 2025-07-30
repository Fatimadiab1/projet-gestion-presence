@extends('layouts.coordinateur')

@section('title', 'Taux de présence moyen par classe')

@vite(['resources/css/coordinateur/calcul/calcul.css'])

@section('content')
<h2 class="title-users">Taux de présence moyen par classe (sur une période)</h2>

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

{{-- Filtres --}}
<form method="GET" class="form-filters">
    <fieldset>
        <legend>Filtrer les résultats</legend>

        <label for="classe_id">Classe :</label>
        <select name="classe_id" id="classe_id">
            <option value="">-- Choisir une classe --</option>
            @foreach ($classes as $classe)
                <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                    {{ $classe->classe->nom }}
                </option>
            @endforeach
        </select>

        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" id="date_debut" value="{{ $dateDebut }}">

        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin" id="date_fin" value="{{ $dateFin }}">

        <button type="submit">Filtrer</button>
    </fieldset>
</form>

{{-- Résultats --}}
@if ($classeId && $dateDebut && $dateFin)
    @if ($resultats)
        <table class="table-style">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Total Séances</th>
                    <th>Total Présences</th>
                    <th>Taux moyen (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $resultats['classe'] }}</td>
                    <td>{{ $resultats['total_seances'] }}</td>
                    <td>{{ $resultats['total_presences'] }}</td>
                    <td>{{ $resultats['taux_moyen'] }} %</td>
                </tr>
            </tbody>
        </table>
    @else
        <p class="no-data">Aucune donnée trouvée pour cette période.</p>
    @endif
@endif
@endsection
