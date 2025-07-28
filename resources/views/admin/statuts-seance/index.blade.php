@extends('layouts.admin')

@section('title', 'Statuts de séance')
@section('header', 'Liste des statuts de séance')

@section('content')
@vite(['resources/css/admin/statutseance/statutseanceindex.css'])

<div class="statut-seance-container">
    <h2 class="titre-page">Liste des statuts de séance</h2>

    {{-- message --}}
    @if(session('success'))
        <div class="alerte-succes">
            {{ session('success') }}
        </div>
    @endif

    {{-- tableau --}}
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statuts as $statut)
                    <tr>
                        <td>#{{ $statut->id }}</td>
                        <td>{{ $statut->nom }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
