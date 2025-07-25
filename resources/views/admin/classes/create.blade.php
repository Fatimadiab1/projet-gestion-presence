@extends('layouts.admin')

@section('title', 'Associer une classe')
@section('header', 'Associer une classe à une année')

@section('content')
    @vite(['resources/css/admin/classe/classeaction.css'])

    <h2>Associer une classe</h2>

    <div class="annee-container">

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

        {{-- Formulaire --}}
        <div class="typeclasse-form">
            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf


                <div class="form-group">
                    <div class="form-box">
                        <label for="classe_id">Classe</label>
                        <select name="classe_id" id="classe_id" required>
                            <option value="">-- Sélectionner une classe --</option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <div class="form-box">
                        <label for="annee_academique_id">Année académique</label>
                        <select name="annee_academique_id" id="annee_academique_id" required>
                            <option value="">-- Sélectionner une année --</option>
                            @foreach ($annees as $annee)
                                <option value="{{ $annee->id }}">{{ $annee->annee }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <div class="form-box">
                        <label for="coordinateur_id">Coordinateur</label>
                        <select name="coordinateur_id" id="coordinateur_id" required>
                            <option value="">-- Sélectionner un coordinateur --</option>
                            @foreach ($coordinateurs as $coordinateur)
                                <option value="{{ $coordinateur->id }}">
                                    {{ $coordinateur->user->nom }} {{ $coordinateur->user->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <div class="form-box">
                        <button type="submit" class="btn-ajouter">Associer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
