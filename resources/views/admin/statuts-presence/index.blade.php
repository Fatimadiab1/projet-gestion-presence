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
