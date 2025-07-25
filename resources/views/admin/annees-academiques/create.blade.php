@extends('layouts.admin')

@section('title', 'Nouvelle année académique')
@section('header', 'Ajouter une année')

@section('content')
    @vite(['resources/css/admin/annee/anneeaction.css'])

    <h2 class="form-title">Créer une année académique</h2>
{{-- message --}}
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
        <form method="POST" action="{{ route('admin.annees-academiques.store') }}">
            @csrf

            <label>Année</label>
            <input type="text" name="annee" placeholder="ex : 2024-2025" value="{{ old('annee') }}" required>

            <label>Date de début</label>
            <input type="date" name="date_debut" value="{{ old('date_debut') }}" required>

            <label>Date de fin</label>
            <input type="date" name="date_fin" value="{{ old('date_fin') }}" required>

            <button type="submit">Ajouter</button>
        </form>
    </div>
@endsection
