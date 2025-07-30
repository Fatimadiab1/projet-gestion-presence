@extends('layouts.coordinateur')

@section('title', 'Étudiants droppés par matière')

@vite(['resources/css/coordinateur/calcul/calcul.css'])

@section('content')
<h2 class="title-users">Étudiants droppés (taux de présence &lt; 30%)</h2>

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

        <label for="matiere_id">Matière :</label>
        <select name="matiere_id" id="matiere_id">
            <option value="">-- Choisir une matière --</option>
            @foreach ($matieres as $matiere)
                <option value="{{ $matiere->id }}" {{ $matiereId == $matiere->id ? 'selected' : '' }}>
                    {{ $matiere->nom }}
                </option>
            @endforeach
        </select>

        <label for="trimestre_id">Trimestre :</label>
        <select name="trimestre_id" id="trimestre_id">
            <option value="">-- Tous les trimestres --</option>
            @foreach ($trimestres as $t)
                <option value="{{ $t->id }}" {{ $trimestreId == $t->id ? 'selected' : '' }}>
                    {{ $t->nom }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filtrer</button>
    </fieldset>
</form>

{{-- Résultats --}}
@if(isset($droppes) && count($droppes) > 0)
    <table class="table-style">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Présences</th>
                <th>Total séances</th>
                <th>Taux (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($droppes as $etudiant)
                <tr>
                    <td>{{ $etudiant['nom'] }}</td>
                    <td>{{ $etudiant['prenom'] }}</td>
                    <td>{{ $etudiant['presences'] }}</td>
                    <td>{{ $etudiant['total'] }}</td>
                    <td style="color: red; font-weight: bold;">{{ $etudiant['taux'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif($classeId && $matiereId)
    <p class="no-data">Aucun étudiant droppé pour cette matière.</p>
@endif
@endsection
