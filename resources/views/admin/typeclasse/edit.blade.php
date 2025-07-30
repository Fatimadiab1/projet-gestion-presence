@extends('layouts.admin')

@section('title', 'Modifier la classe')
@section('header', 'Modifier un type de classe')

@vite(['resources/css/admin/classe/typeclasseaction.css'])

@section('content')
<div class="annee-container">
    <h1><i class="bi bi-pencil-fill"></i> Modifier la classe</h1>

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
        <form action="{{ route('admin.typeclasse.update', $classe->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nom">Nom de la classe</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom', $classe->nom) }}" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
