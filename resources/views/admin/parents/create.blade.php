@extends('layouts.admin')

@section('title', 'Associer un parent')
@section('header', 'Associer un parent à des enfants')

@vite(['resources/css/admin/parent/parentaction.css'])

@section('content')
    <h1 class="form-title"><i class="bi bi-link-45deg"></i> Associer un parent à des enfants</h1>

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

        <label for="etudiants">Enfants à associer</label>
        <select name="etudiants[]" id="etudiants" multiple required>
            @foreach($etudiants as $etudiant)
                <option value="{{ $etudiant->id }}">
                    {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                </option>
            @endforeach
        </select>

        <button type="submit">Associer</button>
    </form>
@endsection
