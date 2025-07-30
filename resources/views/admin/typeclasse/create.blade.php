@extends('layouts.admin')

@section('title', 'Ajouter une classe')
@section('header', 'Ajouter un type de classe')

@vite(['resources/css/admin/classe/typeclasseaction.css'])

@section('content')
<div class="annee-container">
    <h1><i class="bi bi-plus-circle"></i> Nouvelle classe</h1>

    {{-- Messages --}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <div class="typeclasse-form">
        <form action="{{ route('admin.typeclasse.store') }}" method="POST">
            @csrf

            <label for="nom">Nom de la classe</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
