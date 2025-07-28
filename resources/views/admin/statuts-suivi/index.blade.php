@extends('layouts.admin')

@section('title', 'Statuts de suivi')
@section('header', 'Liste des statuts de suivi')

@section('content')
    @vite(['resources/css/admin/statutsuivi/statutsuiviindex.css'])

    <div class="statut-suivi-container">
        <h2 class="titre-page">Liste des statuts de suivi</h2>

        {{-- Message --}}
        @if(session('success'))
            <div class="alerte-succes">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tableau --}}
        <div class="table-responsive">
            <table class="table">
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
                            <td colspan="2">Aucun statut enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
