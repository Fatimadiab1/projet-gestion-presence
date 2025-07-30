@extends('layouts.admin')

@section('title', 'Trimestres')
@section('header', 'Liste des trimestres')

@vite(['resources/css/admin/trimestre/trimestreindex.css'])

@section('content')
<div class="trimestre-container">

    <h2><i class="bi bi-clock-history"></i> Liste des trimestres</h2>

    {{-- Message --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.trimestres.create') }}" class="btn-ajouter">
        <i class="bi bi-plus-lg"></i> Ajouter un trimestre
    </a>

    {{-- Tableau --}}
    <div class="table-wrapper">
        <table class="table" aria-label="Tableau des trimestres">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Année</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trimestres as $trimestre)
                    <tr>
                        <td>{{ $trimestre->nom }}</td>
                        <td>{{ $trimestre->anneeAcademique->annee }}</td>
                        <td>{{ $trimestre->date_debut }}</td>
                        <td>{{ $trimestre->date_fin }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.trimestres.edit', $trimestre->id) }}" class="btn-edit" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.trimestres.destroy', $trimestre->id) }}" method="POST"
                                  onsubmit="return confirm('Supprimer ce trimestre ?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Supprimer">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-message">Aucun trimestre trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
        {{ $trimestres->links() }}
    </div>
</div>
@endsection
