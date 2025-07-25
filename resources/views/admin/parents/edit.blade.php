@extends('layouts.admin')

@section('title', 'Modifier une association')
@section('header', 'Modifier une association')

@section('content')
    @vite(['resources/css/admin/parent/parentaction.css'])

    <h2 class="form-title">
        Modifier l’association du parent : {{ $parent->user->prenom }} {{ $parent->user->nom }}
    </h2>
   {{-- formulaire --}}
    <form method="POST" action="{{ route('admin.parents.update', $parent->id) }}" class="form-container">
        @csrf
        @method('PUT')

        <label for="etudiants">Enfants associés</label>
        <select name="etudiants[]" id="etudiants" multiple required>
            @foreach ($etudiants as $etudiant)
                <option value="{{ $etudiant->id }}" @if ($parent->enfants->contains($etudiant->id)) selected @endif>
                    {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                </option>
            @endforeach
        </select>
        <button type="submit">
            Mettre à jour
        </button>
    </form>
@endsection
