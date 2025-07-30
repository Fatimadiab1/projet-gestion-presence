@extends('layouts.admin')

@section('title', 'Années académiques')
@section('header', 'Gestion des années')

@vite(['resources/css/admin/annee/anneeindex.css'])

@section('content')
<div class="annee-container">
    <h1><i class="bi bi-calendar2-week-fill"></i> Liste des années académiques</h1>
{{-- Message --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.annees-academiques.create') }}" class="btn-ajouter">
     + Ajouter une année
    </a>

{{-- Tableau --}}

 <div class="table-wrapper">
    <table class="table" aria-label="Liste des années">
        <thead>
            <tr>
                <th>Année</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($annees as $annee)
                <tr>
                    <td>{{ $annee->annee }}</td>
                    <td>{{ $annee->date_debut }}</td>
                    <td>{{ $annee->date_fin }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('admin.annees-academiques.edit', $annee->id) }}" class="btn-edit" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            @if ($annee->est_active)
                                <span class="etat-en-cours">Active</span>
                            @else
                                <form action="{{ route('admin.annees-academiques.activer', $annee->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="etat-inactive">Activer</button>
                                </form>
                            @endif

                            <form action="{{ route('admin.annees-academiques.destroy', $annee->id) }}" method="POST" onsubmit="return confirm('Supprimer cette année ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty-message">Aucune année trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="pagination-wrapper">
        {{ $annees->links() }}
    </div>
</div>
@endsection
