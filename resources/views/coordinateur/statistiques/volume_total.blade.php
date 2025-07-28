@extends('layouts.coordinateur')

@section('title', 'Volume total de cours par classe')

@vite(['resources/css/coordinateur/statistique/total.css'])

@section('content')
    <h2 class="title-users">Volume total de cours par classe</h2>

    {{-- Filtre de trimestre --}}
    <div class="trimestre-filtre">
        <label for="selectTrimestre">Afficher :</label>
        <select id="selectTrimestre" onchange="updateChart()">
            <option value="all">Tous les trimestres</option>
            @foreach($trimestres as $index => $trim)
                <option value="{{ $index }}">{{ $trim->nom }}</option>
            @endforeach
        </select>
    </div>

    <div class="chart-container">
        <div class="chart-box">
            <canvas id="volumeTotalChart"></canvas>
        </div>
        <div class="totaux-box">
            <h4>Totaux par classe</h4>
            <ul>
                @php $totalGlobal = 0; @endphp
                @foreach ($donnees as $classe => $heures)
                    @php $somme = array_sum($heures); $totalGlobal += $somme; @endphp
                    <li><strong>{{ $classe }} :</strong> {{ $somme }} h</li>
                @endforeach
            </ul>
            <p class="total-global"><strong>Total général :</strong> {{ $totalGlobal }} h</p>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode(array_keys($donnees)) !!};
    const allData = {!! json_encode($donnees) !!};
    let chart;

    function getChartData(index) {
        return labels.map(classe => {
            const heures = allData[classe];
            return index === 'all' ? heures.reduce((a, b) => a + b, 0) : (heures[index] ?? 0);
        });
    }

    function updateChart() {
        const index = document.getElementById('selectTrimestre').value;
        chart.data.datasets[0].data = getChartData(index);
        chart.update();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('volumeTotalChart');
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Heures totales',
                    data: getChartData('all'),
                    backgroundColor: '#0a0a37',
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: { display: true, text: 'Heures dispensées' }
                    },
                    y: {
                        ticks: { font: { size: 14 } }
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
