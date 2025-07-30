@extends('layouts.parent')

@section('title', 'Dashboard Parent')

@vite(['resources/css/parent/dashboard.css'])

@section('content')
<div class="dashboard-parent">
  {{-- @if (!empty($alertes))
    <div class="popup-alert">
        <div class="popup-content">
            <h3>Alerte de présence</h3>
            <ul>
                @foreach ($alertes as $a)
                    <li>{!! $a !!}</li>
                @endforeach
            </ul>
            <button onclick="document.querySelector('.popup-alert').style.display='none'">Fermer</button>
        </div>
    </div>
@endif --}}

    <div class="header-dashboard">
        <div>
            <p class="date-du-jour">{{ $date }}</p>
            <h2 class="message-bienvenue">
                <strong>Bienvenu, <span class="accent">cher parent !</span></strong>
            </h2>
            <p class="sous-message">Consultez régulièrement le portail parental pour rester informé.</p>

            @if($enfants->count() > 1)
                <select class="select-enfant">
                    @foreach($enfants as $e)
                        <option>{{ $e->user->prenom }} {{ $e->user->nom }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <img src="{{ asset('images/illustration-parent.png') }}" class="image-bienvenue" alt="Illustration parent">
    </div>

   
    @if($enfant)
    <div class="stats-grid">
        <div class="stat-card">
            <i class="bi bi-check2-circle icon"></i>
            <p>Absences justifiées</p>
            <h2>{{ $absencesJustifiees }}</h2>
        </div>

        <div class="stat-card">
            <i class="bi bi-x-circle icon"></i>
            <p>Absences non justifiées</p>
            <h2>{{ $absencesNonJustifiees }}</h2>
        </div>
    </div>
    @endif
</div>
@endsection
