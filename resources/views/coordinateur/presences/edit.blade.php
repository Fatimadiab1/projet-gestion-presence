@extends('layouts.coordinateur')

@section('title', 'Présence - '.$seance->matiere->nom)
@vite(['resources/css/coordinateur/presences/presencesedit.css'])

@section('content')
    <h2 class="form-title">Présences – {{ $seance->classeAnnee->classe->nom }} ({{ $seance->date }})</h2>

    @if($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="formulaire-container">
        <form method="POST" action="{{ route('coordinateur.presences.update', $seance->id) }}">
            @csrf
            @method('PUT')

            @foreach ($etudiants as $e)
                <div class="presence-line">
                    <label>
                        {{ $e->etudiant->user->prenom }} {{ $e->etudiant->user->nom }}
                    </label>

                    <select name="presences[{{ $e->id }}]">
                        @foreach ($statuts as $statut)
                            <option value="{{ $statut->id }}"
                                @if (isset($presencesExistantes[$e->id]) && $presencesExistantes[$e->id]->statut_presence_id == $statut->id)
                                    selected
                                @endif
                            >
                                {{ $statut->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach

            <button type="submit">Enregistrer les présences</button>
        </form>
    </div>
@endsection
