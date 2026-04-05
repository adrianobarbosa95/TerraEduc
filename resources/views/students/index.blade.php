@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <h4 class="mb-4">Alunos</h4>

    {{-- ================= IMPORTAR ================= --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <label>Turma</label>
                        <select name="classroom_id" class="form-control" required>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Arquivo</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-success w-100">Importar Alunos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= FILTRO ================= --}}
    <form method="GET" class="row mb-3">

        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                placeholder="Buscar por nome ou matrícula"
                value="{{ request('search') }}">
        </div>

        <div class="col-md-4">
            <select name="classroom_id" class="form-control">
                <option value="">Todas as turmas</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}"
                        {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary">Filtrar</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Limpar</a>
        </div>

    </form>

    {{-- ================= TABELA ================= --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Turma</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->registration }}</td>
                                <td>{{ $student->classroom->name ?? '-' }}</td>

                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        onclick='openEditModal(@json($student))'>
                                        ✏️
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteStudent({{ $student->id }})">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    Nenhum aluno encontrado
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            <div class="mt-3">
                {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>

{{-- ================= SCRIPTS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ================= EDIT =================
function openEditModal(student) {

    Swal.fire({
        title: 'Editar Aluno',
        html: `
            <input id="name" class="swal2-input" value="${student.name}">
            <input id="registration" class="swal2-input" value="${student.registration}">
        `,
        showCancelButton: true,
        confirmButtonText: 'Salvar',

        preConfirm: () => {
            return {
                id: student.id,
                name: document.getElementById('name').value,
                registration: document.getElementById('registration').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateStudent(result.value);
        }
    });
}

// ================= UPDATE =================
function updateStudent(data) {

    fetch(`/students/${data.id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        Swal.fire('Atualizado!', '', 'success')
            .then(() => location.reload());
    })
    .catch(err => {
        Swal.fire('Erro', err.message, 'error');
    });
}

// ================= DELETE =================
function deleteStudent(id) {

    Swal.fire({
        title: 'Excluir?',
        text: "Não poderá reverter!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
    }).then((result) => {

        if (result.isConfirmed) {

            fetch(`/students/${id}`, {
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

{{-- ALERTAS --}}
@if(session('success'))
<script>
Swal.fire('Sucesso!', "{{ session('success') }}", 'success');
</script>
@endif

@if(session('error'))
<script>
Swal.fire('Erro!', "{{ session('error') }}", 'error');
</script>
@endif

@endsection