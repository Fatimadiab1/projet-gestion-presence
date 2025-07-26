@extends('layouts.admin')

@section('title', 'Modifier un suivi étudiant')
@vite(['resources/css/admin/suivi/suiviaction.css'])

@section('content')
    <h2 class="form-title">Modifier un suivi</h2>

    {{-- Messages d'erreurs --}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <div class="formulaire-container">
        <form method="POST" action="{{ route('admin.suivi-etudiants.update', $suiviEtudiant) }}">
            @csrf
            @method('PUT')

            <label for="inscription_id">Étudiant</label>
            <select name="inscription_id" id="inscription_id" required>
                @foreach ($inscriptions as $i)
                    <option value="{{ $i->id }}" {{ $i->id == $suiviEtudiant->inscription_id ? 'selected' : '' }}>
                        {{ $i->etudiant->user->nom }} {{ $i->etudiant->user->prenom }}
                    </option>
                @endforeach
            </select>

            <label for="statut_suivi_id">Statut</label>
            <select name="statut_suivi_id" id="statut_suivi_id" required>
                @foreach ($statuts as $s)
                    <option value="{{ $s->id }}" {{ $s->id == $suiviEtudiant->statut_suivi_id ? 'selected' : '' }}>
                        {{ $s->nom }}
                    </option>
                @endforeach
            </select>

            <label for="date_decision">Date de la décision</label>
            <input type="date" name="date_decision" id="date_decision" value="{{ $suiviEtudiant->date_decision }}" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
@endsection
