@extends('layouts.admin')

@section('title', 'Ajouter une classe')
@section('header', 'Ajouter un type de classe')

@section('content')
    @vite(['resources/css/admin/classe/typeclasseaction.css'])

    <div class="annee-container">
        <h2>Ajouter une classe</h2>
        {{-- Messages --}}
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
            <form action="{{ route('admin.typeclasse.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <div class="form-box">
                        <label for="nom">Nom de la classe</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-box">
                        <button type="submit" class="btn-ajouter">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
