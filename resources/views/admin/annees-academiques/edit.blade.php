@extends('layouts.admin')

@section('title', 'Modifier une année')
@section('header', 'Modifier une année académique')

@section('content')
    @vite(['resources/css/admin/annee/anneeaction.css'])

    <h2 class="form-title">Modifier l’année : {{ $annee->annee }}</h2>
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
{{-- formulaire --}}
    <div class="formulaire-container">
      <form method="POST" action="{{ route('admin.annees-academiques.update', $annee->id) }}">

            @csrf
            @method('PUT')

            <label>Année</label>
            <input type="text" name="annee" value="{{ old('annee', $annee->annee) }}" required>

            <label>Date de début</label>
            <input type="date" name="date_debut" value="{{ old('date_debut', $annee->date_debut) }}" required>

            <label>Date de fin</label>
            <input type="date" name="date_fin" value="{{ old('date_fin', $annee->date_fin) }}" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
@endsection
