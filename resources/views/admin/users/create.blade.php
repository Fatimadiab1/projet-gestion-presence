@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@vite(['resources/css/admin/user/useraction.css'])

@section('content')
    <h1 class="form-title"><i class="bi bi-person-plus"></i> Création d’un utilisateur</h1>
    {{-- message --}}
    @if ($errors->any())
        <div class="form-alert" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- formulaire --}}
    <div class="form-container">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf

            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>

            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required>

            <label for="email_prefix">Email</label>
            <div class="email-group">
                <input type="text" name="email_prefix" value="{{ old('email_prefix') }}" required>
                <span>@ifran.ci</span>
            </div>

            <label for="mot_de_passe">Mot de passe</label>
            <div class="input-password">
                <input type="password" name="mot_de_passe" id="mot_de_passe" required>
                <i class="bi bi-eye-slash toggle-password" onclick="togglePassword('mot_de_passe', this)"></i>
            </div>


            <label for="mot_de_passe_confirmation">Confirmer le mot de passe</label>
            <div class="input-password">
                <input type="password" name="mot_de_passe_confirmation" id="mot_de_passe_confirmation" required>
                <i class="bi bi-eye-slash toggle-password" onclick="togglePassword('mot_de_passe_confirmation', this)"></i>
            </div>

            <label for="role_id">Rôle</label>
            <select name="role_id" required>
                <option value="">Choisir un rôle</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst($role->nom) }}
                    </option>
                @endforeach
            </select>

            <label for="photo">Photo</label>
            <input type="file" name="photo" accept="image/*">

            <button type="submit">Créer l’utilisateur</button>
        </form>
    </div>
@endsection
{{-- javascript --}}
@section('scripts')
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>
@endsection
