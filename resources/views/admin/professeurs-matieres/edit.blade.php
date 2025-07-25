@extends('layouts.admin')

@section('title', 'Modifier association')
@section('header', 'Modifier matière attribuée')
@vite(['resources/css/admin/prof/profaction.css'])

@section('content')
<h2 class="form-title">Modifier l'association</h2>
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
{{-- tableau --}}
<div class="formulaire-container">
    <form method="POST" action="{{ route('admin.professeurs-matieres.update', [$professeur->id, $matiere->id]) }}">
        @csrf
        @method('PUT')

        <label>Professeur</label>
        <input type="text" value="{{ $professeur->user->nom }} {{ $professeur->user->prenom }}" disabled>

        <label>Matière</label>
        <input type="text" value="{{ $matiere->nom }}" disabled>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection