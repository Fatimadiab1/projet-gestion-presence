@extends('layouts.coordinateur')
@section('title', 'Justifier une absence')
@vite(['resources/css/coordinateur/justification/justificationaction.css'])

@section('content')
<h2 class="form-title">Justifier une absence</h2>

<p><strong>Étudiant :</strong> {{ $presence->etudiant->user->prenom }} {{ $presence->etudiant->user->nom }}</p>
<p><strong>Matière :</strong> {{ $presence->seance->matiere->nom }}</p>
<p><strong>Date :</strong> {{ \Carbon\Carbon::parse($presence->seance->date)->format('d/m/Y') }}</p>

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
<form action="{{ route('coordinateur.justifications.store') }}" method="POST" class="formulaire-container">
    @csrf
    <input type="hidden" name="presence_id" value="{{ $presence->id }}">

    <label for="raison">Raison de l'absence :</label>
    <textarea name="raison" id="raison" required rows="4">{{ old('raison') }}</textarea>

    <button type="submit" class="btn-submit">Valider la justification</button>
</form>
@endsection
