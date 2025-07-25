@extends('layouts.admin')

@section('title', 'Modifier la classe')
@section('header', 'Modifier un type de classe')

@section('content')
    @vite(['resources/css/admin/classe/typeclasseaction.css'])

    <div class="annee-container">
        <h2>Modifier la classe</h2>
{{-- message --}}
        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
{{-- formulaire --}}
        <div class="typeclasse-form">
            <form action="{{ route('admin.typeclasse.update', $classe->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <div class="form-box">
                        <label for="nom">Nom de la classe</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $classe->nom) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-box">
                        <button type="submit" class="btn-ajouter">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
