
@extends('layouts.admin')

@section('title', 'Types de cours')
@vite(['resources/css/admin/typecours/typecoursindex.css'])

@section('content')
<div class="type-cours-container">
    <h2>Liste des types de cours</h2>
    {{-- message --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.types-cours.create') }}" class="btn-ajouter">+ Ajouter un type</a>
{{-- tableau --}}
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($typesCours as $type)

                    <tr>
                        <td>{{ $type->nom }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.types-cours.edit', $type) }}" class="btn-edit" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.types-cours.destroy', $type) }}" method="POST" onsubmit="return confirm('Supprimer ce type ?')" style="display:inline;">
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
