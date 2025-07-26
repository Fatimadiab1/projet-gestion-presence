@extends('layouts.coordinateur')

@section('title', 'Modifier une séance')
@vite(['resources/css/coordinateur/seance/seanceaction.css'])

@section('content')
    <h2 class="form-title">Modifier la séance</h2>

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
        <form method="POST" action="{{ route('coordinateur.seances.update', $seance->id) }}">
            @csrf
            @method('PUT')

            <label>Date :</label>
            <input type="date" name="date" value="{{ old('date', $seance->date) }}" required>

            <label>Jour :</label>
            <input type="text" name="jour_semaine" value="{{ old('jour_semaine', $seance->jour_semaine) }}" required>

            <label>Heure de début :</label>
            <input type="time" name="heure_debut" value="{{ old('heure_debut', $seance->heure_debut) }}" required>

            <label>Heure de fin :</label>
            <input type="time" name="heure_fin" value="{{ old('heure_fin', $seance->heure_fin) }}" required>

            <label>Classe :</label>
            <select name="classe_annee_id" required>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}" {{ $seance->classe_annee_id == $classe->id ? 'selected' : '' }}>
                        {{ $classe->classe->nom }} - {{ $classe->anneeAcademique->annee }}
                    </option>
                @endforeach
            </select>

            <label>Matière :</label>
            <select name="matiere_id" required>
                @foreach ($matieres as $matiere)
                    <option value="{{ $matiere->id }}" {{ $seance->matiere_id == $matiere->id ? 'selected' : '' }}>
                        {{ $matiere->nom }}
                    </option>
                @endforeach
            </select>

            <label>Professeur :</label>
            <select name="professeur_id" required>
                @foreach ($professeurs as $professeur)
                    <option value="{{ $professeur->id }}" {{ $seance->professeur_id == $professeur->id ? 'selected' : '' }}>
                        {{ $professeur->user->prenom }} {{ $professeur->user->nom }}
                    </option>
                @endforeach
            </select>

            <label>Type de cours :</label>
            <select name="type_cours_id" required>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ $seance->type_cours_id == $type->id ? 'selected' : '' }}>
                        {{ $type->nom }}
                    </option>
                @endforeach
            </select>

            <label>Trimestre :</label>
            <select name="trimestre_id" required>
                @foreach ($trimestres as $trimestre)
                    <option value="{{ $trimestre->id }}" {{ $seance->trimestre_id == $trimestre->id ? 'selected' : '' }}>
                        {{ $trimestre->nom }}
                    </option>
                @endforeach
            </select>

            <label>Statut :</label>
            <select name="statut_seance_id" required>
                @foreach ($statuts as $statut)
                    <option value="{{ $statut->id }}" {{ $seance->statut_seance_id == $statut->id ? 'selected' : '' }}>
                        {{ $statut->nom }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
@endsection
