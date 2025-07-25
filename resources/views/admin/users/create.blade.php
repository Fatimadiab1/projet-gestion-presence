@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@section('content')
    @vite(['resources/css/admin/user/useraction.css'])
    <h2 class="form-title">Création d’un utilisateur</h2>

    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Formulaire --}}
    <div class="form-container">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf

            <label>Nom</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required>

            <label>Prénom</label>
            <input type="text" name="prenom" value="{{ old('prenom') }}" required>

            <label>Email</label>
            <div style="display: flex; align-items: center; gap: 8px;">
                <input type="text" name="email_prefix" value="{{ old('email_prefix') }}" required style="flex:1;">
                <span>@ifran.ci</span>
            </div>

            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>

            <label>Confirmer le mot de passe</label>
            <input type="password" name="mot_de_passe_confirmation" required>

            <label>Rôle</label>
            <select name="role_id" required>
                <option value="">Choisir un rôle</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst($role->nom) }}
                    </option>
                @endforeach
            </select>

            <label>Photo</label>
            <input type="file" name="photo" accept="image/*">

            <button type="submit">Créer l’utilisateur</button>

        </form>
    </div>
@endsection
