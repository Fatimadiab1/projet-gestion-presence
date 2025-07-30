@extends('layouts.admin')

@section('title', 'Liste des matières')
@section('header', 'Liste des matières')

@vite(['resources/css/admin/matiere/matiereindex.css'])

@section('content')
<div class="matiere-container">
    <h1><i class="bi bi-journal-text"></i> Liste des matières</h1>

    {{-- Message --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    <div class="action-buttons">
        <a href="{{ route('admin.matieres.create') }}" class="btn-ajouter">
            <i class="bi bi-plus-lg"></i> Ajouter une matière
        </a>
        <a href="{{ route('admin.professeurs-matieres.index') }}" class="btn-associer">
            <i class="bi bi-link-45deg"></i> Associer un professeur
        </a>
    </div>

    {{-- Tableau --}}
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Volume horaire prévu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matieres as $matiere)
                        <tr>
                            <td>{{ $matiere->nom }}</td>
                            <td>{{ $matiere->volume_horaire_prevu }} h</td>
                            <td class="table-actions">
                                <a href="{{ route('admin.matieres.edit', $matiere->id) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.matieres.destroy', $matiere->id) }}" method="POST" onsubmit="return confirm('Supprimer cette matière ?')">
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
                            <td colspan="3" class="text-center">Aucune matière trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            {{ $matieres->links() }}
        </div>
    </div>
</div>
@endsection
