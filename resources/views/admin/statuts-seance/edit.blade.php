@extends('layouts.admin')

@section('title', 'Modifier un statut')
@section('header', 'Modifier le statut de séance')

@section('content')
@vite(['resources/css/admin/statutseance/statutseanceaction.css'])

<h2 class="form-title">Modifier le statut : {{ $statut->nom }}</h2>
{{-- message --}}
@if($errors->any())
    <div class="form-alert">
        <ul>
            @foreach($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{-- formulaire --}}
<div class="formulaire-container">
    <form action="{{ route('admin.statuts-seance.update', $statut->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nom">Nom du statut</label>
        <input type="text" name="nom" id="nom" value="{{ old('nom', $statut->nom) }}" required>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
