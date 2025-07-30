@extends('layouts.admin')

@section('title', 'Statuts de séance')
@section('header', 'Liste des statuts de séance')

@vite(['resources/css/admin/statutseance/statutseanceindex.css'])

@section('content')
<div class="role-header">
    <h1 class="title-roles"><i class="bi bi-collection"></i> Statuts de séance</h1>
</div>
{{-- Message  --}}
@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
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
            @forelse($statuts as $statut)
                <tr>
                    <td>#{{ $statut->id }}</td>
                    <td>{{ $statut->nom }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">Aucun statut trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $statuts->links() }}
    </div>
</div>
@endsection
