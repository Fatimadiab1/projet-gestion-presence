
@extends('layouts.parent')

@section('title', 'Emploi du temps')
@vite('resources/css/parent/emploi.css')

@section('content')

    {{-- Message --}}
    @if(!empty($message))
        <div class="alert-error">{{ $message }}</div>
    @endif

    <h2 class="title-users">
        @if(!empty($enfant) && $enfant->user)
            Emploi du temps de {{ $enfant->user->prenom }} {{ $enfant->user->nom }}
        @else
            Emploi du temps
        @endif
    </h2>

    {{-- Filtre --}}
    <form method="GET" class="form-filters">
        <label for="date_debut">Du :</label>
        <input type="date" name="date_debut" value="{{ request('date_debut') }}">

        <label for="date_fin">Au :</label>
        <input type="date" name="date_fin" value="{{ request('date_fin') }}">

        <button type="submit">Filtrer</button>
    </form>

    {{-- Tableau des séances --}}
    @if(!empty($seances) && $seances->count())
        <table class="emploi-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Matière</th>
                    <th>Type</th>
                    <th>Classe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seances as $s)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                        <td>{{ $s->heure_debut }} - {{ $s->heure_fin }}</td>
                        <td>{{ $s->matiere->nom }}</td>
                        <td>{{ $s->typeCours->nom }}</td>
                        <td>{{ $s->classeAnnee->classe->nom }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(empty($message))
        <p>Aucune séance trouvée pour cette période.</p>
    @endif
@endsection
