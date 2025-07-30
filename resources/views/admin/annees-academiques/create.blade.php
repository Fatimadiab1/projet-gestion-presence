@extends('layouts.admin')

@section('title', 'Nouvelle année académique')
@section('header', 'Ajouter une année')

@section('content')
    @vite(['resources/css/admin/annee/anneeaction.css'])

    <h1 class="form-title"><i class="bi bi-calendar-plus"></i> Créer une année académique</h1>
{{-- Message --}}
    @if ($errors->any())
        <div class="form-alert" role="alert">
            <ul>
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif
{{-- Formulaire --}}
    <div class="formulaire-container">
        <form method="POST" action="{{ route('admin.annees-academiques.store') }}">
            @csrf

            <label for="annee">Année</label>
            <input type="text" id="annee" name="annee" placeholder="ex : 2024-2025" value="{{ old('annee') }}" required>

            <label for="date_debut">Date de début</label>
            <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required>

            <label for="date_fin">Date de fin</label>
            <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin') }}" required>

            <button type="submit">Ajouter</button>
        </form>
    </div>
@endsection
