@extends('layouts.admin')

@section('title', 'Liste des rôles')

@section('content')
    @vite(['resources/css/admin/role.css'])

    <div class="role-header">
        <h1 class="title-roles"><i class="bi bi-shield-lock"></i> Liste des rôles disponibles</h1>
    </div>

    @if ($roles->isEmpty())
        <p class="text">Aucun rôle trouvé.</p>
    @else
        <div class="table-container">
            <table class="style-table" aria-label="Liste des rôles dans le système">
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
                            <td> {{ ucfirst($role->nom) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
