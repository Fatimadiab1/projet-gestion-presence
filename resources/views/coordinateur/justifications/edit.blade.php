@extends('layouts.coordinateur')
@section('title', 'Modifier la justification')
@vite(['resources/css/coordinateur/justification/justificationaction.css'])

@section('content')
<h2 class="form-title">Modifier la justification</h2>

<p><strong>Étudiant :</strong> {{ $justification->presence->etudiant->user->prenom }} {{ $justification->presence->etudiant->user->nom }}</p>
<p><strong>Matière :</strong> {{ $justification->presence->seance->matiere->nom }}</p>
<p><strong>Date :</strong> {{ \Carbon\Carbon::parse($justification->presence->seance->date)->format('d/m/Y') }}</p>

{{-- Erreurs --}}
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
<form action="{{ route('coordinateur.justifications.update', $justification->id) }}" method="POST" class="formulaire-container">
    @csrf
    @method('PUT')

    <label for="raison">Raison de l'absence :</label>
    <textarea name="raison" id="raison" required rows="4">{{ old('raison', $justification->raison) }}</textarea>

    <button type="submit" class="btn-submit">Mettre à jour</button>
</form>
@endsection
