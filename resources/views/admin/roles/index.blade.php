@extends('layouts.admin')

@section('title', 'Liste des rôles')

@section('content')
    @vite(['resources/css/admin/role.css'])
    <h2 class="title-roles">Liste des rôles disponibles</h2>
    @if ($roles->isEmpty())
        <p class="text">Aucun rôle trouvé.</p>
    @else
        <div class="table-container">
            <table class="style-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>#{{ $role->id }}</td>
                            <td>{{ ucfirst($role->nom) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
