@extends('layouts.main')

@section('content')

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>📚 Disciplinas</h4>

        <a href="{{ route('disciplines.create') }}" class="btn btn-primary">
            ➕ Nova Disciplina
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Turma / Unidade / Avaliações</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($disciplines as $discipline)
                        <tr>

                            <td class="fw-semibold">
                                {{ $discipline->name }}
                            </td>

                            <td>
                                @forelse ($discipline->evaluationRules as $rule)
                                    <span class="badge bg-info text-dark me-1 mb-1">
                                        {{ $rule->classroom->name }}
                                        | U{{ $rule->unit }}
                                        : {{ $rule->quantity }} avaliações
                                    </span>
                                @empty
                                    <span class="text-muted">Sem regras</span>
                                @endforelse
                            </td>

                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1">
                                    ✏️
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="deleteDiscipline({{ $discipline->id }})">
                                    🗑️
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Nenhuma disciplina cadastrada</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function deleteDiscipline(id) {

    Swal.fire({
        title: 'Excluir?',
        text: "Não poderá reverter!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
    }).then((result) => {

        if (result.isConfirmed) {

            fetch(`/disciplines/${id}`, {
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