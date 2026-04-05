@extends('layouts.main')

@section('content')

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold">🏫 Turmas</h4>

        <button class="btn btn-primary shadow-sm" onclick="openCreateModal()">
            ➕ Nova Turma
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Modalidade</th>
                            <th>Ano</th>
                            <th>Módulo</th>
                            <th>Período</th>
                            <th>Turma</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($classrooms as $classroom)
                        <tr>
                            <td class="fw-medium">{{ $classroom->name }}</td>
                            <td>{{ $classroom->modality }}</td>
                            <td>{{ $classroom->year }}</td>
                            <td>{{ $classroom->module ?? '-' }}</td>
                            <td>{{ $classroom->period ?? '-' }}</td>
                            <td>{{ $classroom->turma ?? '-' }}</td>

                            <td class="text-end">

                                <button class="btn btn-sm btn-outline-primary me-1"
                                    onclick='openEditModal(@json($classroom))'>
                                    ✏️
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="deleteClassroom({{ $classroom->id }})">
                                    🗑️
                                </button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// ================= CREATE =================
function openCreateModal() {

    Swal.fire({
        title: 'Nova Turma',
        width: 600,
        html: `
            <input id="year" type="number" class="swal2-input" placeholder="Ano">

            <select id="modality" class="swal2-input" onchange="toggleFields(this.value)">
                <option value="">Modalidade</option>
                <option value="PROEJA">PROEJA</option>
                <option value="SUBSEQUENTE">SUBSEQUENTE</option>
                <option value="PROEI">PROEI</option>
                <option value="INTEGRADO">INTEGRADO</option>
            </select>

            <div id="semesterFields" style="display:none;">
                <select id="module" class="swal2-input">
                    <option value="">Módulo</option>
                    <option value="M1">Módulo 1</option>
                    <option value="M2">Módulo 2</option>
                </select>

                <select id="period" class="swal2-input">
                    <option value="">Período</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>

                <input id="turma" class="swal2-input" placeholder="Turma (opcional)">
            </div>

            <div id="annualFields" style="display:none;">
                <input id="serie" class="swal2-input" placeholder="Série (opcional)">
                <input id="turma2" class="swal2-input" placeholder="Turma (opcional)">
            </div>
        `,
        confirmButtonText: 'Salvar',
        showCancelButton: true,

        preConfirm: () => {

            const year = document.getElementById('year').value;
            const modality = document.getElementById('modality').value;
            const module = document.getElementById('module')?.value;
            const period = document.getElementById('period')?.value;
            const serie = document.getElementById('serie')?.value;
            const turma = document.getElementById('turma')?.value || document.getElementById('turma2')?.value;

            if (!year || !modality) {
                Swal.showValidationMessage('Ano e modalidade obrigatórios');
                return false;
            }

            let name = `${year} - ${modality}`;

            return {
                name,
                year,
                modality,
                module,
                period,
                serie: serie || null,
                turma: turma || null,
                units: (modality === 'PROEI' || modality === 'INTEGRADO') ? 3 : 2
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            createClassroom(result.value);
        }
    });
}

function toggleFields(modality) {

    const semester = document.getElementById('semesterFields');
    const annual = document.getElementById('annualFields');

    if (modality === 'PROEJA' || modality === 'SUBSEQUENTE') {
        semester.style.display = 'block';
        annual.style.display = 'none';
    } else {
        semester.style.display = 'none';
        annual.style.display = 'block';
    }
}

// ================= CREATE REQUEST =================
function createClassroom(data) {

    fetch("{{ route('classrooms.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        Swal.fire('Sucesso!', 'Turma criada.', 'success')
            .then(() => location.reload());
    })
    .catch(err => {
        Swal.fire('Erro', err.message, 'error');
    });
}

// ================= EDIT =================
function openEditModal(classroom) {

    Swal.fire({
        title: 'Editar Turma',
        width: 600,
        html: `
            <input id="year" class="swal2-input" value="${classroom.year}">

            <select id="modality" class="swal2-input">
                <option value="PROEJA" ${classroom.modality === 'PROEJA' ? 'selected' : ''}>PROEJA</option>
                <option value="SUBSEQUENTE" ${classroom.modality === 'SUBSEQUENTE' ? 'selected' : ''}>SUBSEQUENTE</option>
                <option value="PROEI" ${classroom.modality === 'PROEI' ? 'selected' : ''}>PROEI</option>
                <option value="INTEGRADO" ${classroom.modality === 'INTEGRADO' ? 'selected' : ''}>INTEGRADO</option>
            </select>

            <input id="module" class="swal2-input" value="${classroom.module || ''}">
            <input id="period" class="swal2-input" value="${classroom.period || ''}">
            <input id="turma" class="swal2-input" value="${classroom.turma || ''}">
        `,
        confirmButtonText: 'Atualizar',
        showCancelButton: true,

        preConfirm: () => {

            return {
                id: classroom.id,
                year: document.getElementById('year').value,
                modality: document.getElementById('modality').value,
                module: document.getElementById('module').value,
                period: document.getElementById('period').value,
                turma: document.getElementById('turma').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateClassroom(result.value);
        }
    });
}

function updateClassroom(data) {

    fetch(`/classrooms/${data.id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        Swal.fire('Atualizado!', 'Turma atualizada.', 'success')
            .then(() => location.reload());
    })
    .catch(err => {
        Swal.fire('Erro', err.message, 'error');
    });
}

// ================= DELETE (CORRIGIDO) =================
function deleteClassroom(id) {

    Swal.fire({
        title: 'Excluir?',
        text: "Não poderá reverter!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
    }).then((result) => {

        if (result.isConfirmed) {

            fetch(`/classrooms/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(() => {
                Swal.fire('Deletado!', '', 'success')
                    .then(() => location.reload());
            })
            .catch(err => {
                Swal.fire('Erro', err.message, 'error');
            });
        }
    });
}

</script>
@endsection