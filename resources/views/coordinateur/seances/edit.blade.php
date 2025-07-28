@extends('layouts.coordinateur')

@section('title', 'Modifier la séance')

@vite(['resources/css/coordinateur/seance/seanceaction.css'])

@section('content')
    <h2 class="form-title">
        Modifier la séance du {{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}
    </h2>

    {{--message--}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
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
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ old('classe_annee_id', $seance->classe_annee_id) == $c->id ? 'selected' : '' }}>
                        {{ $c->classe->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Matière --}}
            <label for="matiere_id">Matière</label>
            <select name="matiere_id" id="matiere_id" required>
                <option value="">Choisir une matière</option>
                @foreach ($matieres as $m)
                    <option value="{{ $m->id }}" {{ old('matiere_id', $seance->matiere_id) == $m->id ? 'selected' : '' }}>
                        {{ $m->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Professeur --}}
            <label for="professeur_id">Professeur (facultatif)</label>
            <select name="professeur_id" id="professeur_id">
                <option value="">Choisir un professeur</option>
                @foreach ($professeurs as $p)
                    <option value="{{ $p->id }}" {{ old('professeur_id', $seance->professeur_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->user->nom }} {{ $p->user->prenom }}
                    </option>
                @endforeach
            </select>

            {{-- Type de cours --}}
            <label for="type_cours_id">Type de cours</label>
            <select name="type_cours_id" id="type_cours_id" required>
                <option value="">Choisir un type</option>
                @foreach ($types as $t)
                    <option value="{{ $t->id }}" {{ old('type_cours_id', $seance->type_cours_id) == $t->id ? 'selected' : '' }}>
                        {{ $t->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Trimestre --}}
            <label for="trimestre_id">Trimestre</label>
            <select name="trimestre_id" required>
                <option value="">Choisir un trimestre</option>
                @foreach ($trimestres as $t)
                    <option value="{{ $t->id }}" {{ old('trimestre_id', $seance->trimestre_id) == $t->id ? 'selected' : '' }}>
                        {{ $t->nom }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Mettre à jour la séance</button>
        </form>
    </div>
@endsection
