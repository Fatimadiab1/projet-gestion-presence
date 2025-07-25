@extends('layouts.admin')

@section('title', 'Liste des trimestres')
@section('header', 'Trimestres')

@section('content')
    @vite(['resources/css/admin/trimestre/trimestreindex.css'])

    <div class="trimestre-container">
        <h2>Liste des trimestres</h2>

        {{-- Message --}}
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('admin.trimestres.create') }}" class="btn-ajouter">
            <i class="bi bi-plus-lg"></i> Ajouter un trimestre
        </a>

        {{-- Tableau --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Trimestre</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Année académique</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trimestres as $trimestre)
                    <tr>
                        <td>{{ $trimestre->nom }}</td>
                        <td>{{ $trimestre->date_debut }}</td>
                        <td>{{ $trimestre->date_fin }}</td>
                        <td>{{ $trimestre->anneeAcademique->annee }}</td>
                        <td>
                            <div class="table-actions">
                           
                                <a href="{{ route('admin.trimestres.edit', $trimestre->id) }}" class="btn-edit" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

    
                                <form action="{{ route('admin.trimestres.destroy', $trimestre->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce trimestre ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Supprimer">
                                        <i class="bi bi-trash" style="color: red"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
