@extends('layouts.admin')

@section('title', 'Ajouter une matière')
@section('header', 'Ajouter une matière')
@vite(['resources/css/admin/matiere/matiereaction.css'])

@section('content')
<h2 class="form-title">Ajouter une matière</h2>
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
    <form action="{{ route('admin.matieres.store') }}" method="POST">
        @csrf

        <label>Nom de la matière</label>
        <input type="text" name="nom" value="{{ old('nom') }}" required>

        <label>Volume horaire prévu (en heures)</label>
        <input type="number" name="volume_horaire_prevu" value="{{ old('volume_horaire_prevu') }}" min="1" required>

        <button type="submit">Enregistrer</button>
    </form>
</div>
@endsection
