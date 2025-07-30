@extends('layouts.admin')

@section('title', 'Modifier une association')
@section('header', 'Modifier une association')

@vite(['resources/css/admin/parent/parentaction.css'])

@section('content')
    <h1 class="form-title">
        <i class="bi bi-pencil-square"></i>
        Modifier l’association du parent : {{ $parent->user->prenom }} {{ $parent->user->nom }}
    </h1>
{{-- Message --}}
    @if ($errors->any())
        <div class="form-alert" role="alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
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
