<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Professeur IFRAN')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Reddit+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite('resources/css/sidebar.css')
</head>

<body>
    <div class="layout-container">
        {{-- sidebar --}}
        <aside class="sidebar">
            <div class="logo-container">
                <img src="{{ asset('images/logo-ifran-light.png') }}" alt="Logo IFRAN">
            </div>

            <nav class="nav-links">
                <a href="{{ route('professeur.dashboard') }}"><i class="bi bi-house-door-fill"></i> Accueil</a>
                <a href="{{ route('professeur.seances.index') }}"><i class="bi bi-easel2"></i> Mes séances</a>
                <a href="{{ route('professeur.presences.index') }}"><i class="bi bi-check2-square"></i> Présences</a>
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
                <h1>Dashboard Professeur</h1>
                <div class="top-right">
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

    @yield('scripts')
</body>

</html>
