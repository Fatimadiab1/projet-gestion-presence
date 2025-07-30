@extends('layouts.etudiant')

@section('title', 'Dashboard Étudiant')

@vite(['resources/css/etudiant/dashboard.css'])

@section('content')
<div class="dashboard-parent">

    @if (!empty($alertes))
    <div class="popup-alert">
        <div class="popup-content">
            <h3>Alerte de présence</h3>
            <ul>
                @foreach ($alertes as $a)
                    <li>{!! $a !!}</li>
                @endforeach
            </ul>
            <button onclick="this.closest('.popup-alert').style.display='none'">Fermer</button>
        </div>
    </div>
    @endif

    <div class="header-dashboard">
        <div>
            <p class="date-du-jour">{{ $date }}</p>
            <h2 class="message-bienvenue">
                <strong>Bienvenu, <span class="accent">{{ Auth::user()->prenom }} !</span></strong>
            </h2>
            <p class="sous-message">Consulte régulièrement ton portail étudiant pour rester informé.</p>

            <div class="fiche-identite">
                <p><strong>Nom :</strong> {{ Auth::user()->nom }}</p>
                <p><strong>Email :</strong> {{ Auth::user()->email }}</p>

                @if ($etudiant->inscriptions->isNotEmpty())
                    @php
                        $classeAnnee = $etudiant->inscriptions->last()->classeAnnee;
                    @endphp
                    <p><strong>Classe :</strong> {{ $classeAnnee->classe->nom ?? 'N/A' }}</p>
                @endif
            </div>
        </div>

        <img src="{{ asset('images/illustration-parent.png') }}" class="image-bienvenue" alt="Illustration étudiant">
    </div>

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
</div>
@endsection
