@extends('layouts.admin')

@section('title', 'Associations Professeurs-Matières')
@section('header', 'Matières attribuées aux professeurs')
@vite(['resources/css/admin/prof/profindex.css'])

@section('content')
<div class="association-container">
    <h2>Liste des matières associées aux professeurs</h2>
{{-- message --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.professeurs-matieres.create') }}" class="btn-ajouter">
        <i class="bi bi-plus-lg"></i> Associer une matière
    </a>
{{-- tableau --}}
    <table class="table">
        <thead>
            <tr>
                <th>Professeur</th>
                <th>Matière</th>
                <th>Volume horaire prévu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($professeurs as $prof)
                @foreach ($prof->matieres as $matiere)
                    <tr>
                        <td>{{ $prof->user->nom }} {{ $prof->user->prenom }}</td>
                        <td>{{ $matiere->nom }}</td>
                        <td>{{ $matiere->volume_horaire_prevu }} h</td>
                        <td><!-- actions ici si besoin --></td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucune association trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
