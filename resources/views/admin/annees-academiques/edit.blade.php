@extends('layouts.admin')

@section('title', 'Modifier une année')
@section('header', 'Modifier une année académique')

@section('content')
    @vite(['resources/css/admin/annee/anneeaction.css'])

    <h1 class="form-title"><i class="bi bi-pencil-square"></i> Modifier l’année : {{ $annee->annee }}</h1>

    {{-- Breadcrumbs --}}
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
        <form method="POST" action="{{ route('admin.annees-academiques.update', $annee->id) }}">
            @csrf
            @method('PUT')

            <label for="annee">Année</label>
            <input type="text" id="annee" name="annee" value="{{ old('annee', $annee->annee) }}" required>

            <label for="date_debut">Date de début</label>
            <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut', $annee->date_debut) }}" required>

            <label for="date_fin">Date de fin</label>
            <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin', $annee->date_fin) }}" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
@endsection
