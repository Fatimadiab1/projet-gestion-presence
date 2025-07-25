@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')

@section('content')
    <h2 class="form-title">Modifier l’utilisateur : {{ $user->prenom }} {{ $user->nom }}</h2>

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
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>Nom</label>
            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required>

            <label>Prénom</label>
            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>

            <label>Email</label>
            <div style="display: flex; align-items: center; gap: 8px;">
                <input type="text" name="email_prefix" value="{{ old('email_prefix', explode('@', $user->email)[0]) }}"
                    required style="flex:1;">
                <span>@ifran.ci</span>
            </div>

            <label>Nouveau mot de passe (laisser vide si inchangé)</label>
            <input type="password" name="mot_de_passe">

            <label>Confirmer le mot de passe</label>
            <input type="password" name="mot_de_passe_confirmation">

            <label>Rôle</label>
            <select name="role_id" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                        {{ ucfirst($role->nom) }}
                    </option>
                @endforeach
            </select>

            <label>Photo</label>
            <input type="file" name="photo" accept="image/*">

            @if ($user->photo)
                <div style="margin-top: 10px;">
                    <strong>Photo actuelle :</strong><br>
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo"
                        style="max-width: 120px; border-radius: 8px;">
                </div>
            @endif

            <button type="submit">Mettre à jour</button>

        </form>
    </div>
@endsection
