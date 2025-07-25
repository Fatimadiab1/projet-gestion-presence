@extends('layouts.admin')

@section('title', 'Liste des matières')
@vite(['resources/css/admin/matiere/matiereindex.css'])

@section('content')
    <div class="matiere-container">
        <h2>Liste des matières</h2>
        <a href="{{ route('admin.professeurs-matieres.index') }}" class="btn-associer">
            <i class="bi bi-link-45deg"></i> Associer un professeur à une matière
        </a>

        {{-- message --}}
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif


        <a href="{{ route('admin.matieres.create') }}" class="btn-ajouter">+ Ajouter une matière</a>

        {{-- tableau --}}
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
                    @foreach ($matieres as $matiere)
                        <tr>
                            <td>{{ $matiere->nom }}</td>
                            <td>{{ $matiere->volume_horaire_prevu }} h</td>
                            <td class="table-actions">
                                <a href="{{ route('admin.matieres.edit', $matiere->id) }}" class="btn-edit"
                                    title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.matieres.destroy', $matiere->id) }}" method="POST"
                                    onsubmit="return confirm('Supprimer cette matière ?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Supprimer">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
