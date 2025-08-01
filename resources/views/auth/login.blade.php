<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Espace personnel de connexion IFRAN pour le suivi des présences des étudiants.">
    <title>Connexion – Espace IFRAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
    @vite('resources/css/app.css')
    @vite('resources/css/login.css')
</head>
<body>
    {{-- Barre du haut --}}
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
{{-- Contenu principal --}}
    <main class="main-content">
        <div class="title-box">
            <h1><span class="highlight">MonPortail</span> IFRAN</h1>
            <p>Votre espace personnel pour le suivi des présences</p>
        </div>

        <div class="login-box">
            <h2>Connexion</h2>

            @if ($errors->any())
                <div class="form-alert">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">Adresse email</label>
                <input class="fill" type="email" id="email" name="email" required placeholder="ex : nom@exemple.com">

                <label for="password">Mot de passe</label>
                <div class="input-password">
                    <input class="fill" type="password" id="password" name="password" required placeholder="Votre mot de passe">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
