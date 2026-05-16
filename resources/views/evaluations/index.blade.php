@extends('layouts.main')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">📊 Avaliações</h4>

    <div class="d-flex gap-2">

        <a href="{{ route('evaluations.index') }}" class="btn btn-primary">
            📚 Todas Avaliações
        </a>

        <a href="{{ route('evaluations.create') }}" class="btn btn-success">
            ➕ Nova Avaliação
        </a>

    </div>

</div>

        {{-- ================= FILTRO ================= --}}
        <form method="GET" class="card p-3 mb-4 shadow-sm">

            <div class="row">

                {{-- TURMA --}}
                <div class="col-md-4">
                    <label>Turma</label>
                    <select name="classroom_id" class="form-control" onchange="this.form.submit()">

                        <option value="">-- Selecione uma turma --</option>

                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} ({{ $classroom->year }})
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- DISCIPLINA --}}
                <div class="col-md-4">
                    <label>Disciplina</label>
                    <select name="discipline_id" class="form-control" onchange="this.form.submit()">

                        <option value="">-- Selecione disciplina --</option>

                        @if (request('classroom_id'))
                            @foreach ($disciplines as $discipline)
                                <option value="{{ $discipline->id }}"
                                    {{ request('discipline_id') == $discipline->id ? 'selected' : '' }}>
                                    {{ $discipline->name }}
                                </option>
                            @endforeach
                        @else
                            <option disabled>Selecione uma turma primeiro</option>
                        @endif

                    </select>
                </div>

                {{-- LIMPAR --}}
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('evaluations.index') }}" class="btn btn-secondary w-100">
                        Limpar
                    </a>
                </div>

            </div>

        </form>
       
@if($evaluations->count())
 
 <div class="d-flex gap-2 justify-content-end mb-3">
<button onclick="window.print()" class="btn btn-dark">
    🖨️ Imprimir
</button>

        <a href="{{ route('evaluations.edit.unit', [
            'classroom_id' => request('classroom_id'),
            'discipline_id' => request('discipline_id'),
            'unit' => request('unit')
        ]) }}" class="btn btn-warning">
            ✏️ Editar Avaliações da Unidade
        </a>

    </div>
        {{-- ================= LISTA ================= --}}
        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover text-center align-middle">

                        <thead class="table-light">
                            <tr>

                                <th>Avaliação</th>
                                <th>Data</th>
                                <th>Disciplina</th>
                                <th>Turma</th>
                                <th>Unidade</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($evaluations as $evaluation)
                                <tr>



                                    <td>
                                        {{ $evaluation->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $evaluation->date ? \Carbon\Carbon::parse($evaluation->date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        {{ $evaluation->discipline->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $evaluation->classroom->name ?? '-' }}
                                    </td>
                                     <td>
                                        {{ $evaluation->unit ?? '-' }}ª
                                    </td>

                                    <td>
                                        {{ $evaluation->description ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $evaluation->value ?? '-' }}
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            <a href="{{ route('grades.show', $evaluation->id) }}"
                                                class="btn btn-sm btn-primary">
                                                📄 Notas
                                            </a>
                                        </button>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-muted">
                                        Nenhuma avaliação encontrada
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
        {{-- ================= IMPRESSÃO ================= --}}
<div class="print-area d-none">

    @php
        $agrupadas = $evaluations->groupBy('classroom.name');
    @endphp

    @foreach($agrupadas as $turma => $avaliacoesTurma)

        <div class="mb-5">

            <h2>
                Turma: {{ $turma }}
            </h2>

            @php
                $disciplinas = $avaliacoesTurma->groupBy('discipline.name');
            @endphp

            @foreach($disciplinas as $disciplina => $avaliacoes)

                <h4 class="mt-4">
                    Disciplina: {{ $disciplina }}
                </h4>

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Avaliação</th>
                            <th>Data</th>
                            <th>Unidade</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($avaliacoes as $evaluation)

                            <tr>

                                <td>{{ $evaluation->name }}</td>

                                <td>
                                    {{ $evaluation->date
                                        ? \Carbon\Carbon::parse($evaluation->date)->format('d/m/Y')
                                        : '-' }}
                                </td>

                                <td>{{ $evaluation->unit }}ª</td>

                                <td>{{ $evaluation->description }}</td>

                                <td>{{ $evaluation->value }}</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @endforeach

        </div>

    @endforeach

</div>
<style>

@media print {
.print-area {
    font-size: 12px;
}

.print-area h2 {
    font-size: 18px;
}

.print-area h4 {
    font-size: 15px;
}

.print-area table {
    font-size: 11px;
}

.print-area th,
.print-area td {
    padding: 4px !important;
}
    body * {
        visibility: hidden;
    }

    .print-area,
    .print-area * {
        visibility: visible;
    }

    .print-area {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
        padding: 20px;
    }

}

</style>
@endif
    </div>

@endsection
