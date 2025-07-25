@extends('layouts.admin')

@section('title', 'Ajouter un type de cours')
@section('header', 'Ajouter un type de cours')

@section('content')
@vite(['resources/css/admin/typecours/typecoursaction.css'])

<h2 class="form-title">Ajouter un type de cours</h2>
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
    <form action="{{ route('admin.types-cours.store') }}" method="POST">
        @csrf

        <label for="nom">Nom du type de cours</label>
        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required>

        <button type="submit">Enregistrer</button>
    </form>
</div>
@endsection