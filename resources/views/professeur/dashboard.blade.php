@extends('layouts.professeur')

@section('title', 'Dashboard Professeur')

@vite(['resources/css/professeur/dashboard.css'])

@section('content')
<div class="dashboard-container">
@if (!empty($alertes))
    <div class="popup-alert">
        <div class="popup-content">
            <h3>Étudiants droppés</h3>
            <ul>
                @foreach ($alertes as $a)
                    <li>{!! $a !!}</li>
                @endforeach
            </ul>
            <button onclick="document.querySelector('.popup-alert').style.display='none'">Fermer</button>
        </div>
    </div>
@endif

    <div class="stats-grid">
        <div class="stat-card">
            <i class="bi bi-calendar-check icon"></i>
            <p>Cours prévus aujourd’hui</p>
            <h2>{{ $coursDuJour->count() }}</h2>
        </div>

        <div class="stat-card">
            <i class="bi bi-clipboard-check icon"></i>
            <p>Fiche de présence à remplir</p>
            <h2>{{ $presencesAEnregistrer }}</h2>
        </div>

        <div class="stat-card">
            <i class="bi bi-journal-bookmark icon"></i>
            <p>Matières enseignées</p>
            <h2>{{ $matieres->count() }}</h2>
        </div>
    </div>

    {{-- Cours du jour --}}
    <div class="cours-jour-box">
        <h4>Cours du jour</h4>

        @if ($coursDuJour->isEmpty())
            <p class="text-muted">Aucun cours prévu aujourd’hui.</p>
        @else
            <ul class="cours-list">
                @foreach ($coursDuJour as $cours)
                    <li>
                        <span class="classe">{{ $cours->classeAnnee->classe->nom }}</span>
                        <span class="matiere">{{ $cours->matiere->nom }}</span>
                        <span class="heure">{{ $cours->heure_debut }}</span>
                        <span class="heure">{{ $cours->heure_fin }}</span>
                        <span class="statut">
                            {{ $cours->statutSeance->nom ?? 'Prévu' }}
                        </span>
                        <span class="report">
                            @if ($cours->statutSeance && strtolower($cours->statutSeance->nom) == 'reportée')
                                {{ \Carbon\Carbon::parse($cours->date_report)->format('d/m/Y à H\hi') }}
                            @else
                                --
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Matières enseignées --}}
    <div class="matieres-box">
        <h4>Matières enseignées</h4>

        @if ($matieres->isEmpty())
            <p class="text-muted">Aucune matière affectée.</p>
        @else
            <ul class="matiere-list">
                @foreach ($matieres as $matiere)
               <li><i class="bi bi-journal-text"></i> {{ $matiere->nom }}</li>

                @endforeach
            </ul>
        @endif
            <div class="pagination-box">
        {{ $matieres->links() }}
    </div>

    </div>

</div>
@endsection
