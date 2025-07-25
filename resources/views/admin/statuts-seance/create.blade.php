@extends('layouts.admin')

@section('title', 'Ajouter un statut')
@section('header', 'Ajouter un statut de séance')

@section('content')
@vite(['resources/css/admin/statutseance/statutseanceaction.css'])

<h2 class="form-title">Ajouter un statut de séance</h2>
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
    <form action="{{ route('admin.statuts-seance.store') }}" method="POST">
        @csrf

        <label for="nom">Nom du statut</label>
        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required>

        <button type="submit">Enregistrer</button>
    </form>
</div>
@endsection
