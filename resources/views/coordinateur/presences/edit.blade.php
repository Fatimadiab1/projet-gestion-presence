@extends('layouts.coordinateur')

@section('title', 'Présences – ' . $seance->matiere->nom)

@vite(['resources/css/coordinateur/presence/presenceaction.css'])

@section('content')
    <h1 class="form-title">
        Gestion des présences – {{ $seance->classeAnnee->classe->nom ?? 'Classe inconnue' }}<br>
        <p>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }} de {{ $seance->heure_debut }} à {{ $seance->heure_fin }}</p>
    </h1>

    {{-- Message--}}
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
    <div class="formulaire-container">
        <form method="POST" action="{{ route('coordinateur.presences.update', $seance->id) }}">
            @csrf
            @method('PUT')

            <div class="presence-table">
                @forelse ($etudiants as $inscription)
                    <div class="presence-row">
                        <div class="etudiant-info">
                            <img src="{{ $inscription->etudiant->user->photo ? asset('storage/' . $inscription->etudiant->user->photo) : asset('images/default-avatar.png') }}"
                                 alt="Photo de {{ $inscription->etudiant->user->prenom }}"
                                 class="etudiant-photo">
                            <div class="etudiant-nom">
                                {{ strtoupper($inscription->etudiant->user->nom) }}
                                {{ ucfirst($inscription->etudiant->user->prenom) }}
                            </div>
                        </div>

               <div class="statut-buttons">
    @foreach ($statuts as $statut)
        @php
            $statutActuel = $presencesExistantes[$inscription->etudiant_id]->statut_presence_id ?? null;
        @endphp
        <label class="statut-carre {{ strtolower($statut->nom) }}">
            <input type="radio"
                   name="presences[{{ $inscription->etudiant_id }}]"
                   value="{{ $statut->id }}"
                   {{ $statutActuel == $statut->id ? 'checked' : '' }}
                   required>
            {{ ucfirst($statut->nom) }}
        </label>
    @endforeach
</div>

                    </div>
                @empty
                    <p class="no-student">Aucun étudiant inscrit à cette classe.</p>
                @endforelse
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Enregistrer les présences</button>
            </div>
        </form>
    </div>
@endsection
