@extends('layouts.etudiant')
@vite(['resources/css/etudiant/emploi.css'])

@section('title', 'Emploi du temps')

@section('content')
    <h2 class="title-users">Mon emploi du temps de la semaine</h2>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if($seances->isEmpty())
        <p style="text-align: center; font-family: 'Reddit Sans', sans-serif;">Aucune séance prévue pour cette période.</p>
    @else
        <table class="emploi-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Matière</th>
                    <th>Type</th>
                    <th>Classe</th>
                    <th>Professeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seances as $seance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($seance->date)->translatedFormat('l d M Y') }}</td>
                        <td>
                            {{ $seance->heure_debut ? \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') : '' }}
                            →
                            {{ $seance->heure_fin ? \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') : '' }}
                        </td>
                        <td>{{ $seance->matiere->nom }}</td>
                        <td>{{ $seance->typeCours->nom }}</td>
                        <td>{{ $seance->classeAnnee->classe->nom }}</td>
                        <td>{{ $seance->professeur?->user?->prenom }} {{ $seance->professeur?->user?->nom }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
