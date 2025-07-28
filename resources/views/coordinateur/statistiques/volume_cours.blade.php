@extends('layouts.coordinateur')

@section('title', 'Volume d’heures de cours par type')

@vite(['resources/css/coordinateur/statistique/cours.css'])

@section('content')
<h2 class="title-users">Volume d’heures de cours par type de cours</h2>

@foreach ($donnees as $typeCours => $trimestresData)
    <div class="bloc-type-cours">
        <h3 class="titre-type">{{ strtoupper($typeCours) }}</h3>

        @php
            $hasData = collect($trimestresData)->flatten(1)->isNotEmpty();
        @endphp

        @if($hasData)
            <div class="grille-graphes">
                @foreach ($trimestres as $trim)
                    @php
                        $donneesTrim = $trimestresData["trim_{$trim->id}"] ?? [];
                    @endphp

                    @if(count($donneesTrim) > 0)
                        <div class="graphe-container">
                            <h4 class="titre-trimestre">{{ $trim->nom }}</h4>
                            <canvas id="chart_{{ Str::slug($typeCours) }}_trim_{{ $trim->id }}"></canvas>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="texte-vide">Aucune donnée disponible pour ce type de cours.</p>
        @endif
    </div>
@endforeach
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    @foreach ($donnees as $typeCours => $trimestresData)
        @foreach ($trimestres as $trim)
            @php
                $donneesTrim = $trimestresData["trim_{$trim->id}"] ?? [];
            @endphp

            @if(count($donneesTrim) > 0)
                const data{{ $loop->parent->index }}{{ $loop->index }} = {!! json_encode($donneesTrim) !!};
                const ctx{{ $loop->parent->index }}{{ $loop->index }} = document.getElementById("chart_{{ Str::slug($typeCours) }}_trim_{{ $trim->id }}");

                if (ctx{{ $loop->parent->index }}{{ $loop->index }}) {
                    new Chart(ctx{{ $loop->parent->index }}{{ $loop->index }}, {
                        type: 'bar',
                        data: {
                            labels: data{{ $loop->parent->index }}{{ $loop->index }}.map(item => item.classe),
                            datasets: [{
                                label: 'Heures de cours',
                                data: data{{ $loop->parent->index }}{{ $loop->index }}.map(item => item.heures),
                                backgroundColor: '#0a0c3c',
                                borderRadius: 5
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: true }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Heures dispensées'
                                    },
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            @endif
        @endforeach
    @endforeach
});
</script>
@endsection
