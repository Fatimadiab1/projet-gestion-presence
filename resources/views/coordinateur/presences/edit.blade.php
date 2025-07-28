@extends('layouts.coordinateur')

@section('title', 'Présences – ' . $seance->matiere->nom)

@vite(['resources/css/coordinateur/presence/presenceaction.css'])

@section('content')
    <h2 class="form-title">
        Gestion des présences - {{ $seance->classeAnnee->classe->nom ?? 'Classe inconnue' }}<br>
        <small>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }} de {{ $seance->heure_debut }} à {{ $seance->heure_fin }}</small>
    </h2>

    {{-- Message --}}
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
                     
                        <div class="etudiant-info" style="display: flex; align-items: center;">
                            <img
                                src="{{ $inscription->etudiant->user->photo ? asset('storage/' . $inscription->etudiant->user->photo) : asset('images/default-avatar.png') }}"
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
                                <label class="checkbox-statut">
                                    <input type="radio"
                                           name="presences[{{ $inscription->etudiant_id }}]"
                                           value="{{ $statut->id }}"
                                           {{ $statutActuel == $statut->id ? 'checked' : '' }}
                                           required>

                                  
                                    <span class="{{ strtolower($statut->nom) === 'présent' ? 'present' : (strtolower($statut->nom) === 'absent' ? 'absent' : (strtolower($statut->nom) === 'en retard' ? 'retard' : '')) }}">
                                        {{ ucfirst($statut->nom) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="no-student">Aucun étudiant inscrit à cette classe.</p>
                @endforelse
            </div>

        
            <div class="form-actions" style="margin-top: 30px; text-align: center;">
                <button type="submit" class="btn-submit">Enregistrer les présences</button>
            </div>
        </form>
    </div>
@endsection
