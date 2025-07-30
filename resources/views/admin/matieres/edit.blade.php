@extends('layouts.admin')

@section('title', 'Modifier la matière')
@section('header', 'Modifier une matière')

@section('content')
    @vite(['resources/css/admin/matiere/matiereaction.css'])

    <h1 class="form-title">Modifier la matière : {{ $matiere->nom }}</h1>

    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="formulaire-container">
        <form action="{{ route('admin.matieres.update', $matiere->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nom">Nom de la matière</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom', $matiere->nom) }}" required>

            <label for="volume_horaire_prevu">Volume horaire prévu (en heures)</label>
            <input type="number" id="volume_horaire_prevu" name="volume_horaire_prevu"
                   value="{{ old('volume_horaire_prevu', $matiere->volume_horaire_prevu) }}" min="1" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
@endsection
