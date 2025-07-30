@extends('layouts.admin')

@section('title', 'Réinscription')
@vite(['resources/css/admin/inscription/reinscription.css'])

@section('content')
    <h1 class="titre-formulaire"><i class="bi bi-plus-circle"></i> Réinscrire un étudiant</h1>

    {{-- Message --}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <div class="formulaire-container">
        <form action="{{ route('admin.inscriptions.reinscrire.store', $etudiant->id) }}" method="POST">
            @csrf

            <label>Étudiant :</label>
            <input type="text" value="{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}" disabled>

            <label for="classe_annee_id">Nouvelle classe et année :</label>
            <select name="classe_annee_id" id="classe_annee_id" required>
                <option value="">-- Sélectionner une classe --</option>
                @foreach ($classesAnnees as $classe)
                    <option value="{{ $classe->id }}" {{ old('classe_annee_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->classe->nom }} ({{ $classe->anneeAcademique->annee }})
                    </option>
                @endforeach
            </select>

            <label for="date_inscription">Date d'inscription :</label>
            <input type="date" name="date_inscription" id="date_inscription" value="{{ old('date_inscription') }}" required>

        
            <button type="submit">Réinscrire</button>
        </form>
    </div>
@endsection
