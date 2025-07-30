@extends('layouts.admin')

@section('title', 'Tableau de bord administratif IFRAN')

@vite('resources/css/admin/dashboard.css')

@section('content')
<h1 class="dashboard-title">Tableau de bord - Accueil</h1>

<div class="dashboard-container">

    {{-- Cartes --}}
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
            <i class="bi bi-easel icon"></i>
            <p>Professeurs</p>
            <h2>{{ $nombreProfesseurs }}</h2>
        </div>
        <div class="stat-card">
            <i class="bi bi-people icon"></i>
            <p>Parents</p>
            <h2>{{ $nombreParents }}</h2>
        </div>
        <div class="stat-card">
            <i class="bi bi-pencil icon"></i>
            <p>Coordinateurs</p>
            <h2>{{ $nombreCoordinateurs }}</h2>
        </div>
    </div>


    <div class="dashboard-right">

        {{-- Année académique --}}
        <div class="academic-year-box">
            <div class="year-box-content">
                <div class="year-text">
                    <h4>Année en cours :</h4>
                    <p class="year">{{ $anneeActuelle->annee ?? 'Non définie' }}</p>
                    <a href="{{ route('admin.annees-academiques.index') }}" class="modify-link">Modifier <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="year-icon">
                    <i class="bi bi-calendar2-week"></i>
                </div>
            </div>
        </div>

        {{-- Derniers utilisateurs --}}
        <div class="recent-users-box">
            <h4>Les 3 derniers inscrits</h4>
            <ul class="user-list">
                @forelse($utilisateurs as $u)
                    <li>
                        <div class="user-info">
                            <span class="avatar">{{ strtoupper(substr($u->prenom, 0, 1)) }}</span>
                            <div>
                                <strong>{{ $u->nom }} {{ $u->prenom }}</strong><br>
                                <span class="email">{{ $u->email }}</span>
                            </div>
                        </div>
                        <span class="role">{{ ucfirst($u->role->nom) }}</span>
                    </li>
                @empty
                    <li>Aucun utilisateur trouvé.</li>
                @endforelse
            </ul>
        </div>

        {{-- Statistiques par role --}}
        <div class="role-stats-box">
            <h4>Répartition des rôles</h4>
            <ul class="role-stats-list">
                @foreach($listeRoles as $role)
                    <li>
                        <span class="role-name">{{ $role->nom }}</span>
                        <span class="role-count">{{ $role->users_count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>
@endsection
