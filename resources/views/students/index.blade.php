@extends('layouts.main')

@section('content')
 
<div class="container-fluid">

    <h4 class="mb-4">Dashboard</h4>

    <div class="row g-4">
        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Turma</label>
                <select name="classroom_id" class="form-control" required>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Arquivo Excel</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <button class="btn btn-success">Importar Alunos</button>
        </form>
    </div>
</div>
<br>
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

<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Matrícula</th>
            <th>Turma</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->registration }}</td>
                <td>{{ $student->classroom->name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Nenhum aluno encontrado</td>
            </tr>
        @endforelse
    </tbody>
</table>
<br>
{{ $students->withQueryString()->links() }}

{{-- SWEET ALERT --}}
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

@endsection