@extends('layouts.coordinateur')

@section('title', 'Liste des séances')
@vite(['resources/css/coordinateur/presences/presencesindex.css'])

@section('content')
    <h2 class="title-users">Gestion des présences</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-container">
        <table class="style-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seances as $s)
                    <tr>
                        <td>{{ $s->date }}</td>
                        <td>{{ $s->heure_debut }} - {{ $s->heure_fin }}</td>
                        <td>{{ $s->classeAnnee->classe->nom ?? 'Classe inconnue' }}</td>
                        <td>{{ $s->matiere->nom ?? 'Matière inconnue' }}</td>
                        <td>
                            <a href="{{ route('coordinateur.presences.edit', $s->id) }}" class="btn-create">
                                <i class="bi bi-check2-square"></i> Gérer présence
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Aucune séance disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
