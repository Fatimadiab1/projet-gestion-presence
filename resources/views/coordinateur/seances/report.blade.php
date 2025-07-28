@extends('layouts.coordinateur')

@section('title', 'Reporter la séance')

@vite(['resources/css/coordinateur/seance/seanceaction.css'])

@section('content')
    <h2 class="form-title">
        Reporter la séance du {{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}
    </h2>

    {{-- message --}}
    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="formulaire-container">
        <form method="POST" action="{{ route('coordinateur.seances.enregistrer-report', $seance->id) }}">
            @csrf

            {{-- Nouvelle date --}}
            <label for="date">Nouvelle date <span class="required">*</span></label>
            <input type="date" name="date" id="date" value="{{ old('date') }}" required>

            {{-- Nouvelle heure de début --}}
            <label for="heure_debut">Heure de début <span class="required">*</span></label>
            <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') }}" required>

            {{-- Nouvelle heure de fin --}}
            <label for="heure_fin">Heure de fin <span class="required">*</span></label>
            <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin') }}" required>

            <button type="submit" class="btn-submit">Enregistrer le report</button>
        </form>
    </div>
@endsection
