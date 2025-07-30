@extends('layouts.admin')

@section('title', 'Associer une matière à un professeur')
@section('header', 'Nouvelle association')
@vite(['resources/css/admin/prof/profaction.css'])

@section('content')
<h1 class="form-title"><i class="bi bi-plus-square"></i> Associer une matière à un professeur</h1>
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
{{-- formulaire --}}
<div class="formulaire-container">
    <form method="POST" action="{{ route('admin.professeurs-matieres.store') }}">
        @csrf

        <label for="professeur_id">Professeur</label>
        <select name="professeur_id" required>
            <option value="">-- Choisir un professeur --</option>
            @foreach ($professeurs as $p)
                <option value="{{ $p->id }}">{{ $p->user->nom }} {{ $p->user->prenom }}</option>
            @endforeach
        </select>

        <label for="matiere_id">Matière</label>
        <select name="matiere_id" required>
            <option value="">-- Choisir une matière --</option>
            @foreach ($matieres as $m)
                <option value="{{ $m->id }}">{{ $m->nom }}</option>
            @endforeach
        </select>

        <button type="submit">Associer</button>
    </form>
</div>
@endsection
