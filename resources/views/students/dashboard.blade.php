@extends('layouts.student')

@section('content')

<div class="container mt-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h3>Portal Acadêmico</h3>
        <p class="text-muted">Clique nas unidades para visualizar as avaliações</p>
    </div>

    <!-- RESUMO -->
    <div class="row g-3 mb-4">

        <div class="col-md-4 d-flex">
            <div class="card border-0 shadow-sm p-3 rounded-3 w-100">
                <small class="text-muted">Aluno</small>
                <h5 class="mb-0">{{ $student->name }}</h5>
                <small class="text-muted">{{ $student->registration }}</small>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card border-0 shadow-sm p-3 rounded-3 w-100">

                <small class="text-muted">Turma</small>
                <h5 class="mb-1">{{ $student->classroom->name ?? '-' }}</h5>

                <div class="text-muted small">
                    {{ $student->classroom->modality ?? '-' }} -
                    {{ $student->classroom->year ?? '-' }}.{{ $student->classroom->period ?? '-' }}
                </div>

            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card border-0 shadow-sm p-3 rounded-3 w-100">
                <small class="text-muted">Média Geral</small>
                <h3 class="mb-0">{{ number_format($student->grades->avg('value') ?? 0, 2) }}</h3>
            </div>
        </div>

    </div>

    <!-- DISCIPLINAS -->
    <h5 class="mb-3">Disciplinas</h5>

    <div class="accordion" id="accordionDisciplinas">

        @foreach($student->classroom->disciplines ?? [] as $discipline)

        @php
            $grades = $student->grades
                ->where('evaluation.discipline_id', $discipline->id)
                ->groupBy(fn($g) => $g->evaluation?->unit);

            $all = $student->grades
                ->where('evaluation.discipline_id', $discipline->id);

            $totalUnits = $student->classroom->units ?? 1;

            $mediaFinal = ($all->sum('value') ?? 0) / $totalUnits;
        @endphp

        <div class="accordion-item mb-3 border-0 shadow-sm rounded-3">

            <!-- DISCIPLINA HEADER -->
            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#disc{{ $discipline->id }}">

                    <div>
                        <strong>{{ $discipline->name }}</strong><br>
                        <small class="text-muted">
                            {{ $discipline->user->name ?? 'Professor não definido' }}
                        </small>
                    
                    </div>

                </button>

            </h2>

            <div id="disc{{ $discipline->id }}"
                 class="accordion-collapse collapse"
                 data-bs-parent="#accordionDisciplinas">

                <div class="accordion-body bg-light">

                    <!-- UNIDADES -->
                    <div class="row g-3 mb-3">

                        @foreach($grades as $unit => $items)

                        @php
                            $media = $items->sum('value');
                        @endphp

                        <div class="col-md-4">

                            <div class="card border-0 shadow-sm p-3 rounded-3 unit-card"
                                 style="cursor:pointer; transition:0.2s; position:relative;"
                                 onmouseover="this.style.transform='scale(1.03)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 onclick="showUnit('{{ $discipline->id }}','{{ $unit }}', event)">

                                <span style="position:absolute; top:8px; right:10px; font-size:12px; opacity:0.5;">
                                    ▼
                                </span>

                                <small class="text-muted">Unidade {{ $unit }}</small>
                                <h5 class="mb-0">{{ $items->count() }} avaliações</h5>
                                <small class="text-muted">
                                    Média: {{ number_format($media, 2) }}
                                </small>

                            </div>

                        </div>

                        @endforeach

                    </div>

                    <!-- TABELA -->
                    <div class="table-responsive">

                        <table class="table table-sm table-striped">

                            <thead>
                                <tr>
                                    <th>Avaliação</th>
                                    <th>Data</th>
                                    <th>Peso</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($student->grades as $grade)

                                    @if($grade->evaluation?->discipline_id == $discipline->id)

                                    <tr class="grade-row"
                                        data-discipline="{{ $discipline->id }}"
                                        data-unit="{{ $grade->evaluation?->unit }}"
                                        style="display:none;">

                                        <td>{{ $grade->evaluation->name }}</td>
                                       <td>{{ \Carbon\Carbon::parse($grade->evaluation->date)->format('d/m/Y') }}</td>
                                        <td>{{ $grade->evaluation->value }}</td>
                                        <td><strong>{{ $grade->value }}</strong></td>

                                    </tr>

                                    @endif

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <!-- MÉDIA FINAL + STATUS -->
                    @php
                        $status = $mediaFinal >= 5 ? 'APROVADO' : 'REPROVADO';
                        $color = $mediaFinal >= 5 ? 'text-success' : 'text-danger';
                    @endphp

                    <div class="mt-3">

                        <div class="card border-0 shadow-sm p-3 rounded-3">
                            <small class="text-muted">Média Final da Disciplina</small>

                            <h3 class="mb-0">{{ number_format($mediaFinal, 2) }}</h3>

                            <small class="{{ $color }}">
                                {{ $status }}
                            </small>

                        </div>

                    </div>
    <a href="{{ route('student.discipline.plan', $discipline->id) }}" 
   class="btn btn-sm btn-outline-primary mt-2">
   Ver Plano de Aula
</a>
                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- CSS ACTIVE -->
<style>
.unit-card.active {
    border: 2px solid #0d6efd !important;
    background: #f8fbff;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
}
</style>

<!-- SCRIPT -->
<script>

function showUnit(disciplineId, unit, event) {

    document.querySelectorAll('.grade-row').forEach(row => {
        if (row.dataset.discipline == disciplineId) {
            row.style.display = (row.dataset.unit == unit) ? '' : 'none';
        }
    });

    document.querySelectorAll('.unit-card').forEach(card => {
        card.classList.remove('active');
    });

    event.currentTarget.classList.add('active');

}

</script>

@endsection