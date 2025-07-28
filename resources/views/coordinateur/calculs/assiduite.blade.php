@extends('layouts.coordinateur')

@section('title', 'Notes d’assiduité')

@vite(['resources/css/coordinateur/calcul/calcul.css'])

@section('content')
<h2 class="title-users">Notes d’assiduité par étudiant et par matière</h2>

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
    <select name="classe_id" onchange="this.form.submit()">
        <option value="">-- Choisir une classe --</option>
        @foreach ($classes as $classe)
            <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                {{ $classe->classe->nom }}
            </option>
        @endforeach
    </select>

    <label for="matiere_id">Matière :</label>
    <select name="matiere_id" onchange="this.form.submit()">
        <option value="">-- Choisir une matière --</option>
        @foreach ($matieres as $matiere)
            <option value="{{ $matiere->id }}" {{ $matiereId == $matiere->id ? 'selected' : '' }}>
                {{ $matiere->nom }}
            </option>
        @endforeach
    </select>

    <label for="trimestre_id">Trimestre :</label>
    <select name="trimestre_id" onchange="this.form.submit()">
        <option value="">-- Choisir un trimestre --</option>
        @foreach ($trimestres as $t)
            <option value="{{ $t->id }}" {{ $trimestreId == $t->id ? 'selected' : '' }}>
                {{ $t->nom }}
            </option>
        @endforeach
    </select>
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
                <th>Note /20</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultats as $etudiant)
                <tr>
                    <td>{{ $etudiant['nom'] }}</td>
                    <td>{{ $etudiant['prenom'] }}</td>
                    <td>{{ $etudiant['nb_presences'] }}</td>
                    <td>{{ $etudiant['nb_seances'] }}</td>
                    <td>{{ $etudiant['note'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif($classeId && $matiereId)
    <p class="no-data">Aucune donnée disponible pour cette combinaison.</p>
@endif
@endsection
