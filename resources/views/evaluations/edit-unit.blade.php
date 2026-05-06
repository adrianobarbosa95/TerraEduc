@extends('layouts.main')

@section('content')

<div class="container mt-4">

    <h4 class="mb-3">✏️ Editar Avaliações por Unidade</h4>

    {{-- ================= FILTRO ================= --}}
    <form method="GET" class="card p-3 mb-3 shadow-sm">

        <div class="row">

            <div class="col-md-4">
                <label>Turma</label>
                <select name="classroom_id" class="form-control">
                    <option value="">Selecione</option>
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}"
                            {{ request('classroom_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Disciplina</label>
                <select name="discipline_id" class="form-control">
                    <option value="">Selecione</option>
                    @foreach($disciplines as $d)
                        <option value="{{ $d->id }}"
                            {{ request('discipline_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Unidade</label>
                <select name="unit" class="form-control">
                    <option value="1" {{ request('unit') == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ request('unit') == 2 ? 'selected' : '' }}>2</option>
                    <option value="3" {{ request('unit') == 3 ? 'selected' : '' }}>3</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    Buscar
                </button>
            </div>

        </div>
 
    </form>

    {{-- ================= FORM EDIÇÃO ================= --}}
    @if($evaluations->count())

    <form method="POST" action="{{ route('evaluations.update.unit') }}">
        @csrf
<input type="hidden" name="classroom_id" value="{{ request('classroom_id') }}">
<input type="hidden" name="discipline_id" value="{{ request('discipline_id') }}">
<input type="hidden" name="unit" value="{{ request('unit') }}">
        <div class="card shadow-sm">

            <div class="card-body">

                {{-- BOTÃO NOVA AVALIAÇÃO --}}
                <div class="mb-3 text-end">
                    <button type="button" class="btn btn-success" onclick="addEvaluation()">
                        ➕ Nova Avaliação
                    </button>
                </div>

                {{-- CONTAINER DINÂMICO --}}
                <div id="evaluation-container">

                    @foreach($evaluations as $ev)

                        <div class="row mb-3 border-bottom pb-2 evaluation-item">

                            <div class="col-md-3">
                                <label>Nome</label>
                                <input type="text"
                                       name="evaluations[{{ $ev->id }}][name]"
                                       class="form-control"
                                       value="{{ $ev->name }}">
                            </div>

                            <div class="col-md-2">
                                <label>Data</label>
                                <input type="date"
                                       name="evaluations[{{ $ev->id }}][date]"
                                       class="form-control"
                                       value="{{ $ev->date }}">
                            </div>

                            <div class="col-md-2">
                                <label>Valor</label>
                                <input type="number"
                                       step="0.1"
                                       name="evaluations[{{ $ev->id }}][value]"
                                       class="form-control grade-input"
                                       value="{{ $ev->value }}">
                            </div>

                            <div class="col-md-4">
                                <label>Descrição</label>
                                <input type="text"
                                       name="evaluations[{{ $ev->id }}][description]"
                                       class="form-control"
                                       value="{{ $ev->description }}">
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="this.closest('.evaluation-item').remove()">
                                    🗑
                                </button>
                            </div>

                        </div>

                    @endforeach

                </div>

                <button class="btn btn-success">
                    💾 Salvar Alterações
                </button>

            </div>

        </div>

    </form>

    @else

        <div class="alert alert-info">
            Selecione turma, disciplina e unidade para editar.
        </div>

    @endif

</div>

{{-- ================= JS ================= --}}
<script>

let tempId = 0;

function addEvaluation() {

    tempId++;

    const container = document.getElementById('evaluation-container');

    const html = `
        <div class="row mb-3 border-bottom pb-2 evaluation-item">

            <div class="col-md-3">
                <label>Nome</label>
                <input type="text"
                       name="evaluations[new_${tempId}][name]"
                       class="form-control">
            </div>

            <div class="col-md-2">
                <label>Data</label>
                <input type="date"
                       name="evaluations[new_${tempId}][date]"
                       class="form-control">
            </div>

            <div class="col-md-2">
                <label>Valor</label>
                <input type="number"
                       step="0.1"
                       name="evaluations[new_${tempId}][value]"
                       class="form-control grade-input">
            </div>

            <div class="col-md-4">
                <label>Descrição</label>
                <input type="text"
                       name="evaluations[new_${tempId}][description]"
                       class="form-control">
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="this.closest('.evaluation-item').remove()">
                    🗑
                </button>
            </div>

        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

</script>

@endsection