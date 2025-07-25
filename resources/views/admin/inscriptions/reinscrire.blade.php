@extends('layouts.admin')

@section('title', 'Réinscription')
@vite(['resources/css/admin/inscription/reinscription.css'])

@section('content')
    <h2 class="titre-formulaire">Réinscrire un étudiant</h2>

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
    <form action="{{ route('admin.inscriptions.reinscrire.store', $etudiant->id) }}" method="POST" class="formulaire-container">
        @csrf

        <label>Étudiant :</label>
        <input type="text" value="{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}" disabled>

        <label for="classe_annee_id">Nouvelle classe & année :</label>
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
@endsection
