@extends('layouts.coordinateur')

@section('title', 'Modifier la séance')

@vite(['resources/css/coordinateur/seance/seanceaction.css'])

@section('content')
    <h1 class="form-title">
        Modifier la séance du {{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}
    </h1>

    {{-- Messages d’erreurs --}}
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

            {{-- Date --}}
            <label for="date">Date</label>
            <input type="date" name="date" value="{{ old('date', $seance->date) }}" required>

            {{-- Heure de début --}}
            <label for="heure_debut">Heure de début</label>
            <input type="time" name="heure_debut" value="{{ old('heure_debut', $seance->heure_debut) }}" required>

            {{-- Heure de fin --}}
            <label for="heure_fin">Heure de fin</label>
            <input type="time" name="heure_fin" value="{{ old('heure_fin', $seance->heure_fin) }}" required>

            {{-- Classe --}}
            <label for="classe_annee_id">Classe</label>
            <select name="classe_annee_id" required>
                <option value="">Choisir une classe</option>
                @foreach ($classes as $classeAnnee)
                    <option value="{{ $classeAnnee->id }}" {{ old('classe_annee_id', $seance->classe_annee_id) == $classeAnnee->id ? 'selected' : '' }}>
                        {{ $classeAnnee->classe->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Matière --}}
            <label for="matiere_id">Matière</label>
            <select name="matiere_id" id="matiere_id" required>
                <option value="">Choisir une matière</option>
                @foreach ($matieres as $matiere)
                    <option value="{{ $matiere->id }}" {{ old('matiere_id', $seance->matiere_id) == $matiere->id ? 'selected' : '' }}>
                        {{ $matiere->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Professeur --}}
            <label for="professeur_id">Professeur (facultatif)</label>
            <select name="professeur_id" id="professeur_id">
                <option value="">Choisir un professeur</option>
                @foreach ($professeurs as $professeur)
                    <option value="{{ $professeur->id }}" {{ old('professeur_id', $seance->professeur_id) == $professeur->id ? 'selected' : '' }}>
                        {{ $professeur->user->nom }} {{ $professeur->user->prenom }}
                    </option>
                @endforeach
            </select>

            {{-- Type de cours --}}
            <label for="type_cours_id">Type de cours</label>
            <select name="type_cours_id" required>
                <option value="">Choisir un type</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_cours_id', $seance->type_cours_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Trimestre --}}
            <label for="trimestre_id">Trimestre</label>
            <select name="trimestre_id" required>
                <option value="">Choisir un trimestre</option>
                @foreach ($trimestres as $trimestre)
                    <option value="{{ $trimestre->id }}" {{ old('trimestre_id', $seance->trimestre_id) == $trimestre->id ? 'selected' : '' }}>
                        {{ $trimestre->nom }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Mettre à jour la séance</button>
        </form>
    </div>
@endsection
