@extends('layouts.admin')

@section('title', 'Années académiques')
@section('header', 'Liste des années')

@section('content')
    @vite(['resources/css/admin/annee/anneeindex.css'])

    <div class="annee-container">
        <h2>Liste des années académiques</h2>
{{-- message --}}
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.annees-academiques.create') }}" class="btn-ajouter">
            <i class="bi bi-plus-lg"></i> Ajouter une année
        </a>
{{-- tableau --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Année</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
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

                                <form action="{{ route('admin.annees-academiques.destroy', $annee->id) }}" method="POST" onsubmit="return confirm('Supprimer cette année ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Supprimer">
                                        <i class="bi bi-trash" style="color: red"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Aucune année trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
