@extends('layouts.admin')

@section('title', 'Modifier une matière')
@section('header', 'Modifier la matière')
@vite(['resources/css/admin/matiere/matiereaction.css'])

@section('content')
<h2 class="form-title">Modifier la matière</h2>
{{-- message --}}
@if($errors->any())
    <div class="form-alert">
        <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{-- Formulaire --}}
<div class="formulaire-container">
    <form action="{{ route('admin.matieres.update', $matiere->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nom de la matière</label>
        <input type="text" name="nom" value="{{ old('nom', $matiere->nom) }}" required>

        <label>Volume horaire prévu (en heures)</label>
        <input type="number" name="volume_horaire_prevu" value="{{ old('volume_horaire_prevu', $matiere->volume_horaire_prevu) }}" min="1" required>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
