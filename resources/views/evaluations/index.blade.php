@extends('layouts.main')

@section('content')

    <div class="container-fluid">

       <div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">📊 Avaliações</h4>

    <a href="{{ route('evaluations.create') }}" class="btn btn-success">
        ➕ Nova Avaliação
    </a>

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
                                        {{ $evaluation->description ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $evaluation->value ?? '-' }}
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            📄
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

    </div>

@endsection
