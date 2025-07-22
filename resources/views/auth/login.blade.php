<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion espace IFRAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />

    <!-- Feuille de style -->
    @vite('resources/css/app.css')
    @vite('resources/css/login.css')
</head>

<body>
    <!-- Barre du haut -->
    <header class="top-bar">
        <div class="logo-container">
            <img src="{{ asset('images/logo-ifran-light.png') }}" alt="Logo IFRAN">
        </div>
        <div class="top-links">
            <a href="https://ifran.ci" target="_blank">
                <i class="fas fa-globe"></i> Site web
            </a>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="main-content">
        <div class="title-box">
            <h1>Espace <span class="highlight">IFRAN</span></h1>
            <p>Accédez à votre espace personnel IFRAN</p>
        </div>

        <div class="login-box">
            <h2>Connexion</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">Identifiant</label>
                <input type="email" id="email" name="email" required placeholder="ex : nom@exemple.com">

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Votre mot de passe">

                <div class="forgot">
                    <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                </div>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>
</body>
</html>
