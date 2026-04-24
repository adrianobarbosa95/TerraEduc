@extends('layouts.main')

@section('content')

<div class="container mt-4">

    <h4 class="mb-4">Lançamento de Notas</h4>

    {{-- FILTROS --}}
    <div class="row mb-3">

        <div class="col-md-4">
            <label>Turma</label>
            <select id="classroom" class="form-control">
                <option value="">Selecione</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" data-period="{{ $c->period }}">
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Disciplina</label>
            <select id="discipline" class="form-control"></select>
        </div>

        <div class="col-md-2">
            <label>Unidade</label>
            <select id="unit" class="form-control">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="loadTable()">Carregar</button>
        </div>

    </div>

    {{-- 🔥 CABEÇALHO --}}
    <div id="info-header" class="mb-3"></div>

    <form method="POST" action="{{ route('grades.store') }}">
        @csrf

        <input type="hidden" name="classroom_id" id="classroom_id">
        <input type="hidden" name="discipline_id" id="discipline_id">
        <input type="hidden" name="unit" id="unit_hidden">

        <div id="table-container"></div>

        <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn btn-success" id="saveBtn" style="display:none;" onclick="confirmSave()">
                💾 Salvar Notas
            </button>

            <button type="button" class="btn btn-secondary" onclick="printTable()">
                🖨️ Imprimir
            </button>

            <button type="button" class="btn btn-success" onclick="exportExcel()">
                📊 Exportar Excel
            </button>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ===============================
// CARREGAR DISCIPLINAS
// ===============================
document.getElementById('classroom').addEventListener('change', function () {

    const classroomId = this.value;
    const disciplineSelect = document.getElementById('discipline');

    disciplineSelect.innerHTML = '<option value="">Selecione</option>';

    if (!classroomId) return;

    disciplineSelect.innerHTML = '<option>Carregando...</option>';

    fetch(`/classrooms/${classroomId}/disciplines`)
        .then(res => res.json())
        .then(data => {

            let options = '<option value="">Selecione</option>';

            data.forEach(d => {
                options += `<option value="${d.id}">${d.name}</option>`;
            });

            disciplineSelect.innerHTML = options;
        })
        .catch(() => {
            disciplineSelect.innerHTML = '<option>Erro ao carregar</option>';
            Swal.fire('Erro!', 'Não foi possível carregar disciplinas.', 'error');
        });
});


// ===============================
// CARREGAR TABELA + CABEÇALHO
// ===============================
function loadTable() {

    const classroomSelect = document.getElementById('classroom');
    const disciplineSelect = document.getElementById('discipline');
    const unit = document.getElementById('unit').value;

    const classroom = classroomSelect.value;
    const discipline = disciplineSelect.value;

    if (!classroom || !discipline || !unit) {
        Swal.fire('Atenção', 'Preencha turma, disciplina e unidade.', 'warning');
        return;
    }

    document.getElementById('classroom_id').value = classroom;
    document.getElementById('discipline_id').value = discipline;
    document.getElementById('unit_hidden').value = unit;

    const classroomText = classroomSelect.options[classroomSelect.selectedIndex].text;
    const disciplineText = disciplineSelect.options[disciplineSelect.selectedIndex].text;
    const period = classroomSelect.options[classroomSelect.selectedIndex].getAttribute('data-period') || '-';
    const year = new Date().getFullYear();

    // 🔥 HEADER
    document.getElementById('info-header').innerHTML = `
        <div class="card shadow-sm">
            <div class="card-body">
                <strong>Turma:</strong> ${classroomText} |
                <strong>Disciplina:</strong> ${disciplineText} |
                <strong>Unidade:</strong> ${unit} |
                <strong>Ano:</strong> ${year} |
                <strong>Período:</strong> ${period} 
                <strong><br>Professor:</strong> {{ auth()->user()->name }}
            </div>
        </div>
    `;

    fetch(`/grades/data?classroom_id=${classroom}&discipline_id=${discipline}&unit=${unit}`)
        .then(res => res.json())
        .then(data => {

            const students = data.students;
            const evaluations = data.evaluations;
            const grades = data.grades || [];

            let html = `<table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Aluno</th>`;

            evaluations.forEach(ev => {
                html += `<th>${ev.name} (${ev.value})</th>`;
            });

            html += `<th>Total</th></tr></thead><tbody>`;

            students.forEach(st => {

                let total = 0;

                html += `<tr><td>${st.name}</td>`;

                evaluations.forEach(ev => {

                    const grade = grades.find(g =>
                        g.student_id == st.id &&
                        g.evaluation_id == ev.id
                    );

                    const value = grade ? grade.value : 0;

                    total += parseFloat(value);

                    html += `
                    <td>
                        <input type="number"
                               step="0.1"
                               name="grades[${st.id}][${ev.id}]"
                               class="form-control grade-input"
                               value="${value}"
                               oninput="calcRow(this)">
                    </td>`;
                });

                html += `<td class="total">${total.toFixed(1)}</td></tr>`;
            });

            html += `</tbody></table>`;

            document.getElementById('table-container').innerHTML = html;
            document.getElementById('saveBtn').style.display = 'block';
        });
}


// ===============================
// SOMA
// ===============================
function calcRow(input) {

    const row = input.closest('tr');
    const inputs = row.querySelectorAll('.grade-input');

    let total = 0;

    inputs.forEach(i => {
        total += parseFloat(i.value || 0);
    });

    row.querySelector('.total').innerText = total.toFixed(1);
}


// ===============================
// CONFIRMAR SENHA
// ===============================
function confirmSave() {

    Swal.fire({
        title: 'Digite sua senha',
        input: 'password',
        showCancelButton: true,
        preConfirm: (password) => {

            return fetch("{{ route('check.password') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ password })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.valid) throw new Error('Senha incorreta');
            })
            .catch(err => {
                Swal.showValidationMessage(err.message);
            });
        }
    }).then(result => {
        if (result.isConfirmed) {
            document.querySelector('form').submit();
        }
    });
}


// ===============================
// IMPRIMIR COM CABEÇALHO
// ===============================
function printTable() {

    const table = document.querySelector("#table-container table");
    const header = document.getElementById('info-header').innerText;

    if (!table) {
        Swal.fire('Atenção', 'Carregue a tabela.', 'warning');
        return;
    }

    let clone = table.cloneNode(true);

    // remove inputs
    clone.querySelectorAll('input').forEach(input => {
        input.parentElement.innerHTML = input.value || '0';
    });

    const win = window.open('', '', 'width=1000,height=800');

    win.document.write(`
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Relatório de Notas</title>

            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }

                .title {
                    text-align: center;
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .subtitle {
                    text-align: center;
                    font-size: 14px;
                    margin-bottom: 20px;
                }

                .info {
                    font-size: 13px;
                    margin-bottom: 15px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }

                th {
                    background: #222;
                    color: #fff;
                    padding: 6px;
                }

                td {
                    border: 1px solid #999;
                    padding: 5px;
                    text-align: center;
                }

                tr:nth-child(even) {
                    background: #f5f5f5;
                }

                .footer {
                    margin-top: 30px;
                    font-size: 12px;
                    text-align: right;
                }
            </style>
        </head>

        <body>

            <div class="title">Diário de Notas</div>
            <div class="subtitle">Relatório Acadêmico</div>

            <div class="info">${header}</div>

            ${clone.outerHTML}

            <div class="footer">
                Gerado em: ${new Date().toLocaleDateString()}
            </div>

        </body>
        </html>
    `);

    win.document.close();
    win.print();
}
// ===============================
// EXPORTAR EXCEL COM CABEÇALHO
// ===============================
function exportExcel() {

    const table = document.querySelector("#table-container table");
    const header = document.getElementById('info-header').innerText;

    if (!table) {
        Swal.fire('Atenção', 'Carregue a tabela.', 'warning');
        return;
    }

    let clone = table.cloneNode(true);

    // remove inputs
    clone.querySelectorAll('input').forEach(input => {
        input.parentElement.innerHTML = input.value || '0';
    });

    let html = `
        <meta charset="UTF-8">

        <table border="0">
            <tr>
                <td colspan="10" style="font-size:16px; font-weight:bold;">
                    Diário de Notas
                </td>
            </tr>

            <tr>
                <td colspan="10">
                    ${header}
                </td>
            </tr>

            <tr><td colspan="10"></td></tr>
        </table>

        ${clone.outerHTML}
    `;

    // 🔥 BOM UTF-8 (RESOLVE ACENTUAÇÃO)
    let blob = new Blob(
        ["\ufeff", html],
        { type: "application/vnd.ms-excel;charset=utf-8;" }
    );

    let url = URL.createObjectURL(blob);

    let link = document.createElement('a');
    link.href = url;
    link.download = 'diario_notas.xls';
    link.click();
}
</script>

@endsection