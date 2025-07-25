@extends('layouts.admin')

@section('title', 'Statuts de présence')
@section('header', 'Liste des statuts de présence')

@section('content')
@vite(['resources/css/admin/statutpresence/statutpresenceindex.css'])

<div class="statut-presence-container">
    <h2 class="titre-page">Liste des statuts de présence</h2>
    {{-- message --}}
    @if(session('success'))
        <div class="alerte-succes">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.statuts-presence.create') }}" class="btn-ajouter">+ Ajouter un statut</a>
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
                @foreach($statuts as $statut)
                    <tr>
                        <td>{{ $statut->nom }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.statuts-presence.edit', $statut) }}" class="btn-edit" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.statuts-presence.destroy', $statut) }}" method="POST" onsubmit="return confirm('Supprimer ?')" style="display:inline;">
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
