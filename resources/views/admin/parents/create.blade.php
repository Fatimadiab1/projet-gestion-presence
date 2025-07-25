@extends('layouts.admin')

@section('title', 'Associer un parent')
@section('header', 'Associer un parent à des enfants')

@section('content')
    @vite(['resources/css/admin/parent/parentaction.css'])

    <h2 class="form-title">Associer un parent à un ou plusieurs enfants</h2>
{{-- formulaire --}}
    <form method="POST" action="{{ route('admin.parents.store') }}" class="form-container">
        @csrf

        <label for="parent_id">Choisir un parent</label>
        <select name="parent_id" id="parent_id" required>
            <option value="">-- Sélectionner un parent --</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}">
                    {{ $parent->user->prenom }} {{ $parent->user->nom }}
                </option>
            @endforeach
        </select>
        <label for="etudiants">Sélectionner un ou plusieurs enfants</label>
        <select name="etudiants[]" id="etudiants" multiple required>
            @foreach($etudiants as $etudiant)
                <option value="{{ $etudiant->id }}">
                    {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                </option>
            @endforeach
        </select>
        <button type="submit">
            Associer
        </button>
    </form>
@endsection
