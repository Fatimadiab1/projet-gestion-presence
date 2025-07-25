@extends('layouts.admin')

@section('title', 'Tableau de bord')

@vite('resources/css/admin/dashboard.css')

@section('content')
    <div class="dashboard-container">
        <div class="stats-grid">
            <div class="stat-card">
                <i class="bi bi-grid icon"></i>
                <p>Classes</p>
                <h2>{{ $nombreClasses }}</h2>
            </div>
            <div class="stat-card">
                <i class="bi bi-layers icon"></i>
                <p>Matières</p>
                <h2>{{ $nombreMatieres }}</h2>
            </div>
            <div class="stat-card">
                <i class="bi bi-mortarboard icon"></i>
                <p>Étudiants</p>
                <h2>{{ $nombreEtudiants }}</h2>
            </div>
            <div class="stat-card">
                <i class="bi bi-pencil icon"></i>
                <p>Coordinateurs</p>
                <h2>{{ $nombreCoordinateurs }}</h2>
            </div>
            <div class="stat-card">
                <i class="bi bi-easel icon"></i>
                <p>Professeurs</p>
                <h2>{{ $nombreProfesseurs }}</h2>
            </div>
            <div class="stat-card">
                <i class="bi bi-people icon"></i>
                <p>Parents</p>
                <h2>{{ $nombreParents }}</h2>
            </div>
        </div>

        <div class="dashboard-right">
            <div class="academic-year-box">
                <div class="year-box-content">
                    <div class="year-text">
                        <h4>Année en cours :</h4>
                        <p class="year">{{ $anneeActuelle->annee ?? 'Non définie' }}</p>
                        <a href="{{ route('admin.annees-academiques.index') }}" class="modify-link">Modifier <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="year-icon">
                        <i class="bi bi-calendar2-week"></i>
                    </div>
                </div>
            </div>

            <div class="user-list-box">
                <div class="header">
                    <h4>Utilisateurs</h4>
                    <p><strong>{{ $nombreUtilisateurs }}</strong> au total</p>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}">
                    <select name="filtre_role" onchange="this.form.submit()">
                        <option value="">Tous les rôles</option>
                        @foreach ($listeRoles as $r)
                            <option value="{{ $r->id }}" {{ request('filtre_role') == $r->id ? 'selected' : '' }}>
                                {{ $r->nom }}</option>
                        @endforeach
                    </select>
                </form>

                <ul class="user-list">
                    @forelse($utilisateurs as $u)
                        <li>
                            <div class="user-info">
                                <span class="avatar">{{ strtoupper(substr($u->prenom, 0, 1)) }}</span>
                                {{ $u->nom }} {{ $u->prenom }}
                            </div>
                            <span class="role">{{ ucfirst($u->role->nom) }}</span>
                        </li>
                    @empty
                        <li>Aucun utilisateur trouvé.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
