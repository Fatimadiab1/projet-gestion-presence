<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Coordinateur IFRAN')</title>
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
                <a href="{{ route('coordinateur.dashboard') }}"><i class="bi bi-house-door-fill"></i> Accueil</a>
                <a href="{{ route('coordinateur.classes') }}"><i class="bi bi-people-fill"></i> Mes classes</a>
                <a href="{{ route('coordinateur.seances.index') }}"><i class="bi bi-easel2"></i> Séances</a>
                <a href="{{ route('coordinateur.presences.index') }}"><i class="bi bi-check-square"></i> Présences</a>
                <a href="{{ route('coordinateur.justifications.index') }}"><i class="bi bi-file-earmark-text"></i> Justifications</a>
                
                <div class="dropdown">
    <a href="#" onclick="toggleDropdown(event, 'calculs-menu')">
        <i class="bi bi-calculator"></i> Calculs
        <i class="bi bi-chevron-down"></i>
    </a>
    <div id="calculs-menu" class="dropdown-content">
        <a href="{{ route('coordinateur.calculs.assiduite') }}">Notes d’assiduité</a>
        <a href="{{ route('coordinateur.calculs.presence.periode') }}">Taux de présence aux cours</a>
     <a href="{{ route('coordinateur.calculs.taux.classe.periode') }}">Taux de présence par classe</a>

        <a href="">Étudiants droppés</a>
    </div>
</div>

                
                
                <div class="dropdown">
    <a href="#" onclick="toggleDropdown(event, 'stats-menu')">
        <i class="bi bi-bar-chart"></i> Statistiques
        <i class="bi bi-chevron-down"></i>
    </a>
    <div id="stats-menu" class="dropdown-content">
        <a href="{{ route('coordinateur.statistiques.presence-etudiants')}}">Taux de présence par étudiant</a>
        <a href="{{ route('coordinateur.statistiques.classes')}}">Taux de présence par classe</a>
        <a href="{{ route('coordinateur.statistiques.volume-cours')}}">Graphe de volume de cours dispensés</a>
        <a href="{{route('coordinateur.statistiques.volume_total')}}">Graphe cumulé de volume de cours dispensés</a>
    </div>
</div>

            </nav>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-link">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>

        {{-- top-bar --}}
        <main class="main-content">
            <header class="top-bar">
                <h1>Dashboard Coordinateur</h1>
                <div class="top-right">
                    <div class="group">
                        <div class="notif-icon">
                            <i class="bi bi-bell-fill"></i>
                            <span>0</span>
                        </div>
                        <div class="avatar-group">
                            <div class="avatar">{{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}</div>
                            <div class="avatar-label">
                                {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
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
    @yield('scripts')

</body>

</html>
