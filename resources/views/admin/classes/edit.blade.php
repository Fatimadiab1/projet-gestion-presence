@extends('layouts.admin')

@section('title', 'Modifier une classe')
@section('header', 'Modifier l’association')

@section('content')
    @vite(['resources/css/admin/classe/classeaction.css'])

    <h2>Modifier une classe associée</h2>

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
            <form method="POST" action="{{ route('admin.classes.update', $classeAnnee->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <div class="form-box">
                        <label for="classe_id">Classe</label>
                        <select name="classe_id" id="classe_id" required>
                            @foreach ($classes as $c)
                                <option value="{{ $c->id }}" {{ $classeAnnee->classe_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-box">
                        <label for="annee_academique_id">Année académique</label>
                        <select name="annee_academique_id" id="annee_academique_id" required>
                            @foreach ($annees as $a)
                                <option value="{{ $a->id }}" {{ $classeAnnee->annee_academique_id == $a->id ? 'selected' : '' }}>
                                    {{ $a->annee }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-box">
                        <label for="coordinateur_id">Coordinateur</label>
                        <select name="coordinateur_id" id="coordinateur_id" required>
                            @foreach ($coordinateurs as $co)
                                <option value="{{ $co->id }}" {{ $classeAnnee->coordinateur_id == $co->id ? 'selected' : '' }}>
                                    {{ $co->user->nom }} {{ $co->user->prenom }}
                                </option>
                            @endforeach
                        </select>
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
