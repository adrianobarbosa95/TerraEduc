@extends('layouts.main')

@section('content')

<div class="container mt-4">

    <h4 class="mb-4">📚 Nova Disciplina</h4>

    <form action="{{ route('disciplines.store') }}" method="POST">
        @csrf

        {{-- NOME --}}
        <div class="mb-3">
            <label>Nome da Disciplina</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- TURMAS --}}
        <div class="mb-3">
            <label>Selecionar Turmas</label>

            @foreach($classrooms as $classroom)
                <div class="border rounded p-3 mb-3">

                    <div class="form-check mb-2">
                        <input class="form-check-input classroom-checkbox"
                               type="checkbox"
                               name="classrooms[]"
                               value="{{ $classroom->id }}"
                               onchange="toggleUnits(this)">

                        <label class="form-check-label fw-semibold">
                            {{ $classroom->name }} ({{ $classroom->units }} unidades)
                        </label>
                    </div>

                    {{-- UNIDADES --}}
                    <div class="units-container" id="units-{{ $classroom->id }}" style="display:none;">
                        @for ($i = 1; $i <= $classroom->units; $i++)
                            <div class="d-flex align-items-center mb-2">

                                <span class="me-2">Unidade {{ $i }}</span>

                                <input type="number"
                                       name="rules[{{ $classroom->id }}][{{ $i }}]"
                                       class="form-control"
                                       style="width:100px"
                                       placeholder="Qtd"
                                       min="1"
                                       disabled> {{-- 🔥 ESSENCIAL --}}
                            </div>
                        @endfor
                    </div>

                </div>
            @endforeach
        </div>

        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('disciplines.index') }}" class="btn btn-secondary">Voltar</a>

    </form>

</div>

{{-- JS --}}
<script>
function toggleUnits(checkbox) {
    const classroomId = checkbox.value;
    const container = document.getElementById(`units-${classroomId}`);
    const inputs = container.querySelectorAll('input');

    if (checkbox.checked) {
        container.style.display = 'block';
        inputs.forEach(i => i.disabled = false);
    } else {
        container.style.display = 'none';
        inputs.forEach(i => i.disabled = true);
        inputs.forEach(i => i.value = ''); // limpa valores
    }
}
</script>

@endsection