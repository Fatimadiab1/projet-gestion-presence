@extends('layouts.admin')

@section('title', 'Modifier un statut')
@section('header', 'Modifier le statut de présence')

@section('content')
    @vite(['resources/css/admin/statutpresence/statutpresenceaction.css'])

    <h2 class="form-title">Modifier le statut : {{ $statutPresence->nom }}</h2>

    {{-- Affichage des erreurs --}}
    @if($errors->any())
        <div class="form-alert">
            <ul>
                @foreach($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire de modification --}}
    <div class="formulaire-container">
        <form action="{{ route('admin.statuts-presence.update', $statutPresence->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nom">Nom du statut</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $statutPresence->nom) }}" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
@endsection
