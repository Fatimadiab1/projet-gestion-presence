@extends('layouts.admin')

@section('title', 'Suivi des étudiants')
@vite(['resources/css/admin/suivi/suiviindex.css'])

@section('content')
<div class="suivi-container">
    <h2>Suivi des étudiants</h2>

    {{-- message --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.suivi-etudiants.create') }}" class="btn-ajouter">+ Ajouter un suivi</a>

    {{-- tableau --}}
    <table class="table">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Statut</th>
                <th>Date de décision</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($suivis as $suiviEtudiant)
                <tr>
                    <td>{{ $suiviEtudiant->inscription->etudiant->user->nom }} {{ $suiviEtudiant->inscription->etudiant->user->prenom }}</td>
                    <td>{{ $suiviEtudiant->statutSuivi->nom }}</td>
                    <td>{{ \Carbon\Carbon::parse($suiviEtudiant->date_decision)->format('d/m/Y') }}</td>
                    <td class="table-actions">
                        <a href="{{ route('admin.suivi-etudiants.edit', $suiviEtudiant) }}" class="btn-edit" title="Modifier">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.suivi-etudiants.destroy', $suiviEtudiant) }}" method="POST" onsubmit="return confirm('Supprimer ce suivi ?')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Supprimer">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucun suivi trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
