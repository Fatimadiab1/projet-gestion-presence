<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Admin IFRAN')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    @vite('resources/css/sidebar.css')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
</head>

<body>
    <div class="layout-container">
        {{-- sidebar --}}
        <aside class="sidebar">
            <div class="logo-container">
                <img src="{{ asset('images/logo-ifran-light.png') }}" alt="Logo IFRAN">
            </div>

            <nav class="nav-links">
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i> Accueil</a>
                <a href="{{ route('admin.roles.index') }}"><i class="bi bi-shield-lock"></i> Rôles</a>
                <div class="dropdown">
                    <a href="#" onclick="toggleDropdown(event, 'users-menu')">
                        <i class="bi bi-people-fill"></i> Utilisateurs
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div id="users-menu" class="dropdown-content">
                        <a href="{{ route('admin.users.index') }}">Liste des utilisateurs</a>
                        <a href="{{ route('admin.users.create') }}">Créer un utilisateur</a>
                        <a href="{{ route('admin.parents.index') }}">Assigner un enfant</a>
                    </div>
                </div>
                <a href="{{ route('admin.annees-academiques.index') }}"><i class="bi bi-calendar2-week"></i> Année
                    académique</a>
                <a href="{{ route('admin.trimestres.index') }}"><i class="bi bi-clock-history"></i> Trimestres</a>
                <a href="{{ route('admin.typeclasse.index') }}"><i class="bi bi-building"></i> Classes</a>
                <a href="{{ route('admin.matieres.index') }}"><i class="bi bi-journal"></i> Matières</a>
                <a href="{{ route('admin.types-cours.index') }}"><i class="bi bi-layers"></i> Types de cours</a>
                <a href="{{ route('admin.statuts-seance.index') }}"><i class="bi bi-calendar-event"></i> Statut de
                    séance</a>
                <a href="{{ route('admin.statuts-presence.index') }}"><i class="bi bi-person-check"></i> Statut de
                    présence</a>
                <a href="{{ route('admin.statuts-suivi.index') }}"><i class="bi bi-clipboard-data"></i> Statut de
                    suivi</a>
                <a href="{{ route('admin.suivi-etudiants.index') }}"><i class="bi bi-person-check-fill"></i> Suivi des
                    étudiants</a>
                <a href="{{ route('admin.inscriptions.index') }}"><i class="bi bi-diagram-3"></i> Étudiants
                    inscrits</a>
                <a href="{{ route('admin.inscriptions.non_reinscrits') }}"><i class="bi bi-exclamation-triangle"></i>
                    Réinscriptions</a>
            </nav>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="logout-link">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>
        {{-- top-bar --}}
        <main class="main-content">
            <header class="top-bar">
                <h1>Dashboard Administrateur</h1>
                <div class="top-right">
                    <div class="group">
                        <div class="notif-icon">
                            <i class="bi bi-bell-fill"></i>
                            <span>1</span>
                        </div>
                        <div class="avatar-group">
                            <div class="avatar">A</div>
                            <div class="avatar-label">Admin</div>
                        </div>
                    </div>
                </div>
            </header>

            <section class="page-content">
                @yield('content')
            </section>
        </main>
    </div>
    <script>
        function toggleDropdown(event, id) {
            event.preventDefault();
            const dropdown = document.getElementById(id);
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>

</html>
