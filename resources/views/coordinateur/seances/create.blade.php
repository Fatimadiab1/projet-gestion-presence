@extends('layouts.coordinateur')

@section('title', 'Créer une séance')
@vite(['resources/css/coordinateur/seance/seanceaction.css'])

@section('content')
    <h2 class="form-title">Créer une nouvelle séance</h2>

    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="formulaire-container">
        <form method="POST" action="{{ route('coordinateur.seances.store') }}">
            @csrf

            <label>Date :</label>
            <input type="date" name="date" value="{{ old('date') }}" required>

            <label>Jour :</label>
            <input type="text" name="jour_semaine" value="{{ old('jour_semaine') }}" required>

            <label>Heure de début :</label>
            <input type="time" name="heure_debut" value="{{ old('heure_debut') }}" required>

            <label>Heure de fin :</label>
            <input type="time" name="heure_fin" value="{{ old('heure_fin') }}" required>

            <label>Classe :</label>
            <select name="classe_annee_id" required>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}">{{ $classe->classe->nom }} - {{ $classe->anneeAcademique->annee }}</option>
                @endforeach
            </select>

            <label>Matière :</label>
            <select name="matiere_id" required>
                @foreach ($matieres as $matiere)
                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                @endforeach
            </select>

            <label>Professeur :</label>
            <select name="professeur_id" required>
                @foreach ($professeurs as $professeur)
                    <option value="{{ $professeur->id }}">{{ $professeur->user->prenom }} {{ $professeur->user->nom }}</option>
                @endforeach
            </select>

            <label>Type de cours :</label>
            <select name="type_cours_id" required>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}">{{ $type->nom }}</option>
                @endforeach
            </select>

            <label>Trimestre :</label>
            <select name="trimestre_id" required>
                @foreach ($trimestres as $trimestre)
                    <option value="{{ $trimestre->id }}">{{ $trimestre->nom }}</option>
                @endforeach
            </select>

            <label>Statut :</label>
            <select name="statut_seance_id" required>
                @foreach ($statuts as $statut)
                    <option value="{{ $statut->id }}">{{ $statut->nom }}</option>
                @endforeach
            </select>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
@endsection
