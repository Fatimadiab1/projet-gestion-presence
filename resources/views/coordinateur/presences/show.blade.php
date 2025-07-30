@extends('layouts.coordinateur')

@section('title', 'Fiche présence – ' . $seance->matiere->nom)

@vite(['resources/css/coordinateur/presence/presenceshow.css'])

@section('content')
    <h2 class="form-title">
        Fiche de présence – {{ $seance->classeAnnee->classe->nom ?? 'Classe inconnue' }}<br>
        <small>{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }} de {{ $seance->heure_debut }} à {{ $seance->heure_fin }}</small>
    </h2>

    <div class="presence-container">
       @if (empty($etudiants))
            <p class="no-student">Aucun étudiant inscrit à cette classe.</p>
        @else
            <table class="presence-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom de l’étudiant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($etudiants as $inscription)
                        @php $etudiant = $inscription->etudiant; @endphp
                        <tr>
                            <td>
                                <img
                                    src="{{ $etudiant->user->photo ? asset('storage/' . $etudiant->user->photo) : asset('images/default-avatar.png') }}"
                                    alt="Photo de {{ $etudiant->user->prenom }}"
                                    class="etudiant-photo">
                            </td>
                            <td>
                                {{ strtoupper($etudiant->user->nom) }} {{ ucfirst($etudiant->user->prenom) }}
                            </td>
                          <td>
    @php
        $presence = $presencesExistantes[$etudiant->id] ?? null;
        $statutNom = $presence ? $statuts->firstWhere('id', $presence->statut_presence_id)?->nom : null;
    @endphp

    @if ($statutNom)
        @if (strtolower($statutNom) === 'présent')
            <span class="statut-text present">{{ $statutNom }}</span>
        @elseif (strtolower($statutNom) === 'absent')
            <span class="statut-text absent">{{ $statutNom }}</span>
        @elseif (strtolower($statutNom) === 'en retard')
            <span class="statut-text retard">{{ $statutNom }}</span>
        @else
            <span class="statut-text">{{ $statutNom }}</span>
        @endif
    @else
        <span class="statut-text">Non renseigné</span>
    @endif
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="form-actions">
        <a href="{{ route('coordinateur.presences.index') }}" class="btn-return">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
@endsection
