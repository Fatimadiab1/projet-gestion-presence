@extends('layouts.etudiant')

@section('title', 'Note d’assiduité')

@vite(['resources/css/etudiant/assiduite.css'])

@section('content')
<h2 class="title-users">Note d’assiduité</h2>

<form method="GET" class="form-filters">
    <label for="matiere_id">Matière :</label>
    <select name="matiere_id" onchange="this.form.submit()">
        <option value="">Choisir une matière</option>
        @foreach ($matieres as $m)
            <option value="{{ $m->id }}" {{ $m->id == $matiereId ? 'selected' : '' }}>
                {{ $m->nom }}
            </option>
        @endforeach
    </select>

    <label for="trimestre_id">Trimestre :</label>
    <select name="trimestre_id" onchange="this.form.submit()">
        <option value="">Tous les trimestres</option>
        @foreach ($trimestres as $t)
            <option value="{{ $t->id }}" {{ $t->id == $trimestreId ? 'selected' : '' }}>
                {{ $t->nom }}
            </option>
        @endforeach
    </select>
</form>

@if(count($resultats))
    <table class="table-style">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Présences</th>
                <th>Nombre de séances</th>
                <th>Note /20</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultats as $r)
                <tr>
                    <td>{{ Auth::user()->prenom }}</td>
                    <td>{{ Auth::user()->nom }}</td>
                    <td>{{ $r['nb_presences'] }}</td>
                    <td>{{ $r['nb_seances'] }}</td>
                    <td>{{ $r['note'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif($matiereId)
    <p class="no-data">Aucune donnée trouvée.</p>
@endif
@endsection
