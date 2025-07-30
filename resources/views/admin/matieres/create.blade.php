@extends('layouts.admin')

@section('title', 'Ajouter une matière')
@section('header', 'Nouvelle matière')

@section('content')
    @vite(['resources/css/admin/matiere/matiereaction.css'])

    <h1 class="form-title"><i class="bi bi-plus-circle"></i> Ajouter une matière</h1>

{{-- Message --}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}

    <div class="formulaire-container">
        <form action="{{ route('admin.matieres.store') }}" method="POST">
            @csrf

            <label for="nom">Nom de la matière</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>

            <label for="volume_horaire_prevu">Volume horaire prévu (en heures)</label>
            <input type="number" id="volume_horaire_prevu" name="volume_horaire_prevu" value="{{ old('volume_horaire_prevu') }}" min="1" required>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
@endsection
