@extends('layouts.admin')

@section('title', 'Types de cours')
@section('header', 'Liste des types de cours')

@vite(['resources/css/admin/typecours/typecoursindex.css'])

@section('content')
    <div class="role-header">
        <h1 class="title-roles"><i class="bi bi-easel-fill"></i> Types de cours</h1>
    </div>
{{-- Message  --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
{{-- Tableau --}}
    <div class="table-container">
        <table class="style-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                @forelse($typesCours as $type)
                    <tr>
                        <td>#{{ $type->id }}</td>
                        <td>{{ $type->nom }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-muted text-center">Aucun type de cours trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $typesCours->links() }}
        </div>
    </div>
@endsection
