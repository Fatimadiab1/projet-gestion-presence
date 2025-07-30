@extends('layouts.admin')

@section('title', 'Professeurs et matières')
@section('header', 'Matières attribuées aux professeurs')

@vite(['resources/css/admin/prof/profindex.css'])

@section('content')
<div class="association-container">
    <h1><i class="bi bi-person-lines-fill"></i> Liste des matières par professeur</h1>

    {{-- Message --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.professeurs-matieres.create') }}" class="btn-ajouter">
        <i class="bi bi-plus-lg"></i> Associer une matière
    </a>

    {{-- Tableau --}}
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Professeur</th>
                    <th>Matière</th>
                    <th>Volume horaire</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($professeurs as $prof)
                    @foreach ($prof->matieres as $matiere)
                        <tr>
                            <td>{{ $prof->user->nom }} {{ $prof->user->prenom }}</td>
                            <td>{{ $matiere->nom }}</td>
                            <td>{{ $matiere->volume_horaire_prevu }} h</td>
                            <td class="table-actions">
                                <a href="{{ route('admin.professeurs-matieres.edit', [$prof->id, $matiere->id]) }}" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucune association trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $professeurs->links() }}
    </div>
</div>
@endsection
