@extends('layouts.admin')

@section('title', 'Types de classes')
@section('header', 'Liste des types de classes')

@section('content')
    @vite(['resources/css/admin/classe/typeclasseindex.css'])

    <div class="annee-container">
        <h2>Liste des types de classes</h2>
{{-- message --}}
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
            <a href="{{ route('admin.typeclasse.create') }}" class="btn-ajouter">
                <i class="bi bi-plus-lg"></i> Ajouter une classe
            </a>

            <a href="{{ route('admin.classes.index') }}" class="btn-associer">
                <i class="bi bi-link-45deg"></i> Voir et associer une classe à une année
            </a>
        </div>
{{-- tableau --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classes as $classe)
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
                                        <i class="bi bi-trash" style="color:red;"></i>
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
