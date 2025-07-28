@extends('layouts.admin')

@section('title', 'Types de cours')
@section('header', 'Liste des types de cours')
@vite(['resources/css/admin/typecours/typecoursindex.css'])

@section('content')
<div class="type-cours-container">
    <h2 class="titre-page">Liste des types de cours</h2>

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
                @foreach($typesCours as $type)
                    <tr>
                        <td>#{{ $type->id }}</td>
                        <td>{{ $type->nom }}</td>
                   
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
