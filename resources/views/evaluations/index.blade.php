@extends('layouts.main')

@section('content')

<div class="container">

    <h4>Nova Avaliação</h4>

    <form action="{{ route('evaluations.store') }}" method="POST">
        @csrf

        {{-- TURMA --}}
        <div class="mb-3">
            <label>Turma</label>
            <select name="classroom_id" id="classroom" class="form-control" onchange="loadClassroomData()" required>
                <option value="">Selecione</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- DISCIPLINA --}}
        <div class="mb-3">
            <label>Disciplina</label>
            <select name="discipline_id" id="discipline" class="form-control" required>
                <option value="">Selecione a turma primeiro</option>
            </select>
        </div>

        {{-- UNIDADE --}}
        <div class="mb-3">
            <label>Unidade</label>
            <select name="unit" id="unit" class="form-control" required>
                <option value="">Selecione a turma</option>
            </select>
        </div>

        <hr>

        {{-- AVALIAÇÕES --}}
        <h5>Avaliações</h5>

        <div id="evaluations"></div>

        <button type="button" class="btn btn-primary mt-2" onclick="addEvaluation()">
            ➕ Adicionar Avaliação
        </button>

        <div class="mt-3">
            <strong>Total: <span id="total">0</span> / 10</strong>
        </div>

        <br>

        <button id="saveBtn" class="btn btn-success" disabled>
            Salvar Avaliações
        </button>

    </form>
</div>

{{-- SCRIPT --}}
<script>

let count = 0;

// 🔥 CARREGA DISCIPLINAS E UNIDADES
function loadClassroomData() {

    const classroomId = document.getElementById('classroom').value;

    if (!classroomId) return;

    fetch(`/classrooms/${classroomId}/data`)
        .then(res => res.json())
        .then(data => {

            // DISCIPLINAS
            let disciplineSelect = document.getElementById('discipline');
            disciplineSelect.innerHTML = '<option value="">Selecione</option>';

            data.disciplines.forEach(d => {
                disciplineSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
            });

            // UNIDADES
            let unitSelect = document.getElementById('unit');
            unitSelect.innerHTML = '<option value="">Selecione</option>';

            for (let i = 1; i <= data.units; i++) {
                unitSelect.innerHTML += `<option value="${i}">Unidade ${i}</option>`;
            }

        })
        .catch(err => {
            console.error(err);
            alert('Erro ao carregar dados da turma');
        });
}

// 🔥 ADICIONAR AVALIAÇÃO
function addEvaluation() {

    count++;

    const html = `
        <div class="card p-3 mb-2 evaluation-item">

            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="evaluations[${count}][name]" 
                        class="form-control" placeholder="Nome" required>
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.1" name="evaluations[${count}][value]" 
                        class="form-control value-input" placeholder="Valor" 
                        required oninput="calculateTotal()">
                </div>

                <div class="col-md-3">
                    <input type="date" name="evaluations[${count}][date]" class="form-control">
                </div>

                <div class="col-md-3">
                    <input type="text" name="evaluations[${count}][description]" 
                        class="form-control" placeholder="Descrição">
                </div>

                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeEvaluation(this)">
                        X
                    </button>
                </div>
            </div>

        </div>
    `;

    document.getElementById('evaluations').insertAdjacentHTML('beforeend', html);
}

// 🔥 REMOVER
function removeEvaluation(btn) {
    btn.closest('.evaluation-item').remove();
    calculateTotal();
}

// 🔥 SOMA AUTOMÁTICA
function calculateTotal() {

    let total = 0;
    let countItems = document.querySelectorAll('.evaluation-item').length;

    document.querySelectorAll('.value-input').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    total = parseFloat(total.toFixed(2));

    document.getElementById('total').innerText = total;

    const saveBtn = document.getElementById('saveBtn');

    // REGRA
    if (total === 10 && countItems >= 3) {
        saveBtn.disabled = false;
    } else {
        saveBtn.disabled = true;
    }
}

// 🔥 INICIA COM 3
addEvaluation();
addEvaluation();
addEvaluation();

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: "{{ session('success') }}"
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: "{{ session('error') }}"
    });
</script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erro de validação',
        html: `{!! implode('<br>', $errors->all()) !!}`
    });
</script>

@endif
@endsection