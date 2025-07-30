@extends('layouts.admin')

@section('title', 'Ajouter un trimestre')
@section('header', 'Nouveau trimestre')

@section('content')
    @vite(['resources/css/admin/trimestre/trimestreaction.css'])

    <h2 class="form-title"><i class="bi bi-calendar-plus"></i> Ajouter un trimestre</h2>

    {{-- Messages --}}
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
        <form action="{{ route('admin.trimestres.store') }}" method="POST">
            @csrf

            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required>

            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}" required>

            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}" required>

            <label for="annee_academique_id">Année académique</label>
            <select name="annee_academique_id" id="annee_academique_id" required>
                <option value="">-- Choisir une année --</option>
                @foreach ($annees as $annee)
                    <option value="{{ $annee->id }}" {{ old('annee_academique_id') == $annee->id ? 'selected' : '' }}>
                        {{ $annee->annee }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
@endsection
