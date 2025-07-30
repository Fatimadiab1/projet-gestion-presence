@extends('layouts.admin')

@section('title', 'Modifier association')
@section('header', 'Modifier matière attribuée')
@vite(['resources/css/admin/prof/profaction.css'])

@section('content')
<h1 class="form-title"><i class="bi bi-pencil-square"></i> Modifier l'association</h1>
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
<div class="formulaire-container">
    <form method="POST" action="{{ route('admin.professeurs-matieres.update', [$professeur->id, $matiere->id]) }}">
        @csrf
        @method('PUT')

        <label>Professeur</label>
        <input type="text" value="{{ $professeur->user->nom }} {{ $professeur->user->prenom }}" disabled>

        <label>Matière actuelle</label>
        <input type="text" value="{{ $matiere->nom }}" disabled>

        <label for="nouvelle_matiere_id">Nouvelle matière</label>
        <select name="nouvelle_matiere_id" id="nouvelle_matiere_id" required>
            <option value="">-- Choisir une nouvelle matière --</option>
            @foreach ($matieres as $m)
                @if ($m->id !== $matiere->id)
                    <option value="{{ $m->id }}">{{ $m->nom }}</option>
                @endif
            @endforeach
        </select>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
