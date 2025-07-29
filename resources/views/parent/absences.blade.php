@extends('layouts.parent')

@section('title', 'Liste des absences')
@vite('resources/css/parent/absences.css')

@section('content')
    <h2 class="title-users">Absences de l’enfant</h2>
    <form method="GET" class="form-filters">
        <input type="hidden" name="enfant_id" value="{{ $enfantId }}">
        <label for="trimestre_id">Trimestre :</label>
        <select name="trimestre_id" onchange="this.form.submit()">
            @foreach ($trimestres as $trimestre)
                <option value="{{ $trimestre->id }}" {{ $trimestreId == $trimestre->id ? 'selected' : '' }}>
                    {{ $trimestre->nom }} ({{ $trimestre->anneeAcademique->libelle }})
                </option>
            @endforeach
        </select>
    </form>

  
    <div class="bloc-absences">
        <h3 class="titre-section rouge">Absences non justifiées</h3>
        @if ($absencesNonJustifiees->isEmpty())
            <p class="texte-vide">Aucune absence non justifiée.</p>
        @else
            <table class="table-absences">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Matière</th>
                        <th>Type de cours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absencesNonJustifiees as $a)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($a->seance->date)->format('d/m/Y') }}</td>
                            <td>{{ $a->seance->matiere->nom }}</td>
                            <td>{{ $a->seance->typeCours->nom }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="bloc-absences">
        <h3 class="titre-section vert">Absences justifiées</h3>
        @if ($absencesJustifiees->isEmpty())
            <p class="texte-vide">Aucune absence justifiée.</p>
        @else
            <table class="table-absences">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Matière</th>
                        <th>Type de cours</th>
                        <th>Justification</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absencesJustifiees as $a)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($a->seance->date)->format('d/m/Y') }}</td>
                            <td>{{ $a->seance->matiere->nom }}</td>
                            <td>{{ $a->seance->typeCours->nom }}</td>
                            <td>{{ $a->justification->raison }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

   
    <div class="bloc-absences">
        <h3 class="titre-section bleu">Présences</h3>
        @if ($presencesNormales->isEmpty())
            <p class="texte-vide">Aucune présence enregistrée.</p>
        @else
            <table class="table-absences">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Matière</th>
                        <th>Type de cours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($presencesNormales as $a)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($a->seance->date)->format('d/m/Y') }}</td>
                            <td>{{ $a->seance->matiere->nom }}</td>
                            <td>{{ $a->seance->typeCours->nom }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
