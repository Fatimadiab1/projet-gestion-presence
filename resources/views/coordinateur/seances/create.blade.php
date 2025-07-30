@extends('layouts.coordinateur')

@section('title', 'Ajouter une séance')

@vite(['resources/css/coordinateur/seance/seanceaction.css'])

@section('content')
    <h2 class="form-title"><i class="bi bi-plus-circle"></i> Ajouter une séance</h2>

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

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="formulaire-container">
        <form method="POST" action="{{ route('coordinateur.seances.store') }}">
            @csrf

            {{-- Date de la séance --}}
            <label for="date">Date</label>
            <input type="date" name="date" value="{{ old('date') }}" required>

            {{-- Heure de début --}}
            <label for="heure_debut">Heure de début</label>
            <input type="time" name="heure_debut" value="{{ old('heure_debut') }}" required>

            {{-- Heure de fin --}}
            <label for="heure_fin">Heure de fin</label>
            <input type="time" name="heure_fin" value="{{ old('heure_fin') }}" required>

            {{-- Classe concernée --}}
            <label for="classe_annee_id">Classe</label>
            <select name="classe_annee_id" required>
                <option value="">Choisir une classe</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ old('classe_annee_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->classe->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Matière concernée --}}
            <label for="matiere_id">Matière</label>
            <select name="matiere_id" id="matiere_id" required>
                <option value="">Choisir une matière</option>
                @foreach ($matieres as $m)
                    <option value="{{ $m->id }}" {{ old('matiere_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Professeur --}}
            <label for="professeur_id">Professeur (facultatif)</label>
            <select name="professeur_id" id="professeur_id">
                <option value="">Choisir un professeur</option>
      
            </select>

            {{-- Type de cours --}}
            <label for="type_cours_id">Type de cours</label>
            <select name="type_cours_id" id="type_cours_id" required>
                <option value="">Choisir un type</option>
                @foreach ($types as $t)
                    <option value="{{ $t->id }}" {{ old('type_cours_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->nom }}
                    </option>
                @endforeach
            </select>

            {{-- Trimestre concerné --}}
            <label for="trimestre_id">Trimestre</label>
            <select name="trimestre_id" required>
                <option value="">Choisir un trimestre</option>
                @foreach ($trimestres as $t)
                    <option value="{{ $t->id }}" {{ old('trimestre_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->nom }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Créer la séance</button>
        </form>
    </div>

    <script>
        const professeursParMatiere = @json(
            $matieres->mapWithKeys(function($m) {
                return [$m->id => $m->professeurs->map(function($p) {
                    return [
                        'id' => $p->id,
                        'nom' => $p->user->nom . ' ' . $p->user->prenom
                    ];
                })];
            })
        );

 
        document.getElementById('matiere_id').addEventListener('change', function () {
            const profSelect = document.getElementById('professeur_id');
            const profs = professeursParMatiere[this.value] || [];


            profSelect.innerHTML = '<option value="">Choisir un professeur</option>';


            profs.forEach(function(p) {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.nom;
                profSelect.appendChild(opt);
            });
        });
    </script>
@endsection
