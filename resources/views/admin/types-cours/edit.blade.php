
@extends('layouts.admin')

@section('title', 'Modifier un type de cours')
@section('header', 'Modifier le type de cours')

@section('content')
@vite(['resources/css/admin/typecours/typecoursaction.css'])

<h2 class="form-title">Modifier le type : {{ $type->nom }}</h2>
{{-- message --}}
@if($errors->any())
    <div class="form-alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{-- formulaire --}}
<div class="formulaire-container">
    <form action="{{ route('admin.types-cours.update', $type->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nom">Nom du type de cours</label>
        <input type="text" name="nom" id="nom" value="{{ old('nom', $type->nom) }}" required>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection