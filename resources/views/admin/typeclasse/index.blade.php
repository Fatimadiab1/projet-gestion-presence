@extends('layouts.admin')

@section('title', 'Types de classes')
@section('header', 'Liste des types de classes')

@vite(['resources/css/admin/classe/typeclasseindex.css'])

@section('content')
<div class="annee-container">

    <h1><i class="bi bi-door-open-fill"></i> Types de classes</h1>
{{-- message --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="top-bar-buttons">
        <a href="{{ route('admin.typeclasse.create') }}" class="btn-ajouter">
            <i class="bi bi-plus-lg"></i> Ajouter une classe
        </a>
        <a href="{{ route('admin.classes.index') }}" class="btn-associer">
            <i class="bi bi-link-45deg"></i> Associer à une année
        </a>
    </div>
{{-- Tableau --}}
    <div class="table-wrapper">
        <table class="table" aria-label="Liste des types de classes">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($classes as $classe)
                    <tr>
                        <td>{{ $classe->nom }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.typeclasse.edit', $classe->id) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.typeclasse.destroy', $classe->id) }}" method="POST" onsubmit="return confirm('Supprimer cette classe ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Supprimer">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="empty-message">Aucune classe trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination-wrapper">
            {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection
