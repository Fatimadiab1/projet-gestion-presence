@extends('layouts.coordinateur')

@section('title', 'Taux de présence par classe')


@section('content')
    <h2 class="title-users">Taux de présence par classe</h2>

    {{-- Filtres --}}
    <form method="GET" class="form-filters">
        <label for="date_debut">Début :</label>
        <input type="date" name="date_debut" value="{{ request('date_debut') }}">

        <label for="date_fin">Fin :</label>
        <input type="date" name="date_fin" value="{{ request('date_fin') }}">

        <button type="submit">Filtrer</button>
    </form>

    {{-- Graphique --}}
    @if (!empty($resultats))
        <div class="statistique-container">
            <canvas id="classeChart"></canvas>
        </div>
    @else
        <p class="no-data">Aucune donnée trouvée pour cette période.</p>
    @endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('classeChart');
        const labels = {!! json_encode(array_column($resultats ?? [], 'classe')) !!};
        const data = {!! json_encode(array_column($resultats ?? [], 'taux')) !!};
        const colors = data.map(taux => {
            if (taux >= 70) return '#006400';
            if (taux >= 50) return '#90ee90';
            if (taux >= 30) return '#ffa500';
            return '#dc3545';
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Taux de présence (%)',
                    data: data,
                    backgroundColor: colors,
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        max: 100,
                        title: {
                            display: true,
                            text: 'Taux (%)'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            }
        });
    });
</script>
@endsection
