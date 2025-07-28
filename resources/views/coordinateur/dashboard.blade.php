@extends('layouts.coordinateur')

@section('title', 'Dashboard Coordinateur')

@vite(['resources/css/coordinateur/dashboard.css'])

@section('content')
<div class="dashboard-container">

    {{-- Cartes --}}
    <div class="stats-grid">
        <div class="stat-card">
            <i class="bi bi-person-dash icon"></i>
            <p>Étudiants absents aujourd’hui</p>
            <h2>{{ $absentsAujourdhui }}</h2>
        </div>

        <div class="stat-card">
            <i class="bi bi-people icon"></i>
            <p>Étudiants suivis</p>
            <h2>{{ $nombreEtudiants }}</h2>
        </div>

        <div class="stat-card">
            <i class="bi bi-pencil icon"></i>
            <p>Présence à enregistrer</p>
            <h2>{{ $presencesAEnregistrer }}</h2>
        </div>

        <div class="stat-card">
            <i class="bi bi-exclamation-triangle icon"></i>
            <p>Absences non justifiées</p>
            <h2>{{ $absencesNonJustifiees }}</h2>
        </div>
    </div>

    {{-- Graphique --}}
    <div class="main-grid">

        {{-- 📈 Graphique taux de présence --}}
        <div class="presence-graph-box">
            <h4>Taux de présence par classe</h4>

            <form method="GET" class="form-periode">
                <label>Choisir la période</label>
                <div class="periode-fields">
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" required>
                    <span>au</span>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" required>
                    <button type="submit">Afficher</button>
                </div>
            </form>

            @if (!empty($tauxClasses))
                <canvas id="tauxClasseChart"></canvas>
            @else
                <p class="text-muted">Aucune donnée à afficher.</p>
            @endif
        </div>

        {{-- Liste --}}
        <div class="etudiants-list-box">
            <h4>Liste des étudiants</h4>

            <form method="GET">
                <select name="classe_id" onchange="this.form.submit()">
                    <option value="">Filtrer par classe</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ request('classe_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->classe->nom }}
                        </option>
                    @endforeach
                </select>
            </form>

            <ul class="etudiant-list">
                @foreach ($etudiants as $e)
                    <li>
                        <div class="avatar">
                            {{ strtoupper(substr($e->user->prenom, 0, 1)) }}
                        </div>
                        <div class="etudiant-info">
                            <div class="nom-prenom">
                                {{ $e->user->nom }} {{ $e->user->prenom }}
                            </div>
                            <div class="classe">
                                @foreach ($e->inscriptions as $i)
                                    {{ $i->classeAnnee->classe->nom }}
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
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
                        <span class="heure">{{ \Carbon\Carbon::parse($cours->heure_debut)->format('H:i') }}</span>
                        <span class="type">{{ ucfirst($cours->typeCours->nom) }}</span>
                        <span class="classe">{{ $cours->classeAnnee->classe->nom }}</span>
                        <span class="matiere">{{ $cours->matiere->nom }}</span>
                        <span class="prof" style="color: #FF0047; font-weight: bold;">
                            {{ $cours->professeur ? $cours->professeur->user->nom . ' ' . $cours->professeur->user->prenom : '--' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
@endsection


@section('scripts')
@if (!empty($tauxClasses))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('tauxClasseChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($tauxClasses)) !!},
                datasets: [{
                    label: 'Taux de présence (%)',
                    data: {!! json_encode(array_values($tauxClasses)) !!},
                    backgroundColor: '#0a0a37',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
@endif
@endsection
