@extends('layouts.admin')

@section('title', 'Modifier l’inscription')
@vite(['resources/css/admin/inscription/inscriptionaction.css'])

@section('content')
    <h2 class="form-title">Modifier l’inscription</h2>

  {{-- message --}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <div class="formulaire-container">
        <form action="{{ route('admin.inscriptions.update', $inscription) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="etudiant_id">Étudiant</label>
            <select name="etudiant_id" id="etudiant_id" required>
                <option value="">-- Sélectionner un étudiant --</option>
                @foreach ($etudiants as $etudiant)
                    <option value="{{ $etudiant->id }}"
                        {{ old('etudiant_id', $inscription->etudiant_id) == $etudiant->id ? 'selected' : '' }}>
                        {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                    </option>
                @endforeach
            </select>

            <label for="classe_annee_id">Classe & année</label>
            <select name="classe_annee_id" id="classe_annee_id" required>
                <option value="">-- Sélectionner une classe --</option>
                @foreach ($classesAnnees as $classe)
                    <option value="{{ $classe->id }}"
                        {{ old('classe_annee_id', $inscription->classe_annee_id) == $classe->id ? 'selected' : '' }}>
                        {{ $classe->classe->nom }} ({{ $classe->anneeAcademique->annee }})
                    </option>
                @endforeach
            </select>

            <label for="date_inscription">Date d'inscription</label>
            <input type="date" name="date_inscription" id="date_inscription"
                value="{{ old('date_inscription', $inscription->date_inscription) }}" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
@endsection
