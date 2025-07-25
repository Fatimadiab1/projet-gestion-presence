@extends('layouts.admin')

@section('title', 'Liste des associations')
@section('header', 'Associations Parents ↔ Enfants')

@section('content')
    @vite(['resources/css/admin/parent/parentindex.css'])

    <div class="association-container">
        <h2>Associations Enfants-Parents</h2>

       {{-- barre --}}
        <div class="top-bar-association">
            <a href="{{ route('admin.parents.create') }}" class="btn-create">
                <i class="bi bi-plus-lg"></i> Ajouter une association
            </a>

            <form method="GET" action="{{ route('admin.parents.index') }}" class="search-form">
                <div class="search-wrapper">
                    <input type="text" name="recherche" placeholder="Rechercher un enfant..." value="{{ $recherche }}">
                    <button type="submit" class="search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- message --}}
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- tableau --}}
        <div class="table-wrapper">
            <table class="style-table">
                <thead>
                    <tr>
                        <th>Parent</th>
                        <th>Enfants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parents as $parent)
                        <tr>
                            <td>{{ $parent->user->prenom }} {{ $parent->user->nom }}</td>
                            <td>
                                @forelse($parent->enfants as $enfant)
                                    <span class="badge">
                                        {{ $enfant->user->prenom }} {{ $enfant->user->nom }}
                                    </span>
                                @empty
                                    <span class="text-muted">Aucun enfant</span>
                                @endforelse
                            </td>
                            <td class="table-actions">
                                <a href="{{ route('admin.parents.edit', $parent->id) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-message">Aucune association trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
