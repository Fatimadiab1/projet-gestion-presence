@extends('layouts.admin')

@section('title', 'Ajouter un suivi étudiant')
@vite(['resources/css/admin/suivi/suiviaction.css'])

@section('content')
    <h2 class="form-title">Ajouter un suivi</h2>
    {{-- message --}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- formulaire --}}
    <div class="formulaire-container">
        <form method="POST" action="{{ route('admin.suivi-etudiants.store') }}">
            @csrf

            <label for="inscription_id">Étudiant</label>
            <select name="inscription_id" required>
                <option value="">Choisir un étudiant</option>
                @foreach ($inscriptions as $inscription)
                    <option value="{{ $inscription->id }}">
                        {{ $inscription->etudiant->user->nom }} {{ $inscription->etudiant->user->prenom }}
                    </option>
                @endforeach
            </select>

            <label for="statut_suivi_id">Statut</label>
            <select name="statut_suivi_id" required>
                <option value="">Choisir un statut</option>
                @foreach ($statuts as $statut)
                    <option value="{{ $statut->id }}">{{ $statut->nom }}</option>
                @endforeach
            </select>

            <label for="date_decision">Date de la décision</label>
            <input type="date" name="date_decision" required>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
@endsection
