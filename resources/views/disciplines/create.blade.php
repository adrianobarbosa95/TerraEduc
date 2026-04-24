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

                    {{-- CHECK --}}
                    <div class="form-check mb-2">
                        <input class="form-check-input classroom-checkbox"
                               type="checkbox"
                               name="classrooms[]"
                               value="{{ $classroom->id }}"
                               onchange="toggleClassroom(this)">

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
                                       disabled>
                            </div>
                        @endfor
                    </div>

                    {{-- 🔥 HORÁRIOS VISUAIS --}}
                    <div class="schedule-container mt-3" id="schedule-{{ $classroom->id }}" style="display:none;">

                        <label class="fw-semibold">Horários</label>

                        @php
                            $days = [
                                2 => 'Segunda',
                                3 => 'Terça',
                                4 => 'Quarta',
                                5 => 'Quinta',
                                6 => 'Sexta'
                            ];

                            $shifts = [
                                'M' => 6,
                                'T' => 6,
                                'N' => 5
                            ];
                        @endphp

                        @foreach($days as $dayNumber => $dayName)

                            <div class="border rounded p-2 mb-2">

                                <strong>{{ $dayName }}</strong>

                                @foreach($shifts as $shift => $max)

                                    <div class="mt-2">

                                        <small>
                                            {{ $shift == 'M' ? 'Manhã' : ($shift == 'T' ? 'Tarde' : 'Noite') }}
                                        </small>

                                        <div class="d-flex flex-wrap gap-2 mt-1">

                                            @for($i = 1; $i <= $max; $i++)
                                                <label class="btn btn-outline-primary btn-sm">
                                                    <input type="checkbox"
                                                           class="slot-checkbox"
                                                           data-classroom="{{ $classroom->id }}"
                                                           data-day="{{ $dayNumber }}"
                                                           data-shift="{{ $shift }}"
                                                           value="{{ $i }}"
                                                           disabled>
                                                    {{ $i }}
                                                </label>
                                            @endfor

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endforeach

                        {{-- hidden que vai enviar pro controller --}}
                        <textarea name="schedules[{{ $classroom->id }}]"
                                  id="hidden-schedule-{{ $classroom->id }}"
                                  hidden></textarea>

                    </div>

                </div>
            @endforeach
        </div>

        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('disciplines.index') }}" class="btn btn-secondary">Voltar</a>

    </form>

</div>

<script>
function toggleClassroom(checkbox) {

    const id = checkbox.value;

    const units = document.getElementById(`units-${id}`);
    const schedules = document.getElementById(`schedule-${id}`);

    const unitInputs = units.querySelectorAll('input');
    const checkboxes = schedules.querySelectorAll('.slot-checkbox');

    if (checkbox.checked) {

        units.style.display = 'block';
        schedules.style.display = 'block';

        unitInputs.forEach(i => i.disabled = false);
        checkboxes.forEach(c => c.disabled = false);

    } else {

        units.style.display = 'none';
        schedules.style.display = 'none';

        unitInputs.forEach(i => {
            i.disabled = true;
            i.value = '';
        });

        checkboxes.forEach(c => {
            c.checked = false;
            c.disabled = true;
        });

        updateSchedules(id);
    }
}

// 🔥 MONTA O FORMATO AUTOMATICAMENTE
document.addEventListener('change', function(e) {

    if (e.target.classList.contains('slot-checkbox')) {

        const classroomId = e.target.dataset.classroom;
        updateSchedules(classroomId);
    }
});

function updateSchedules(classroomId) {

    const checkboxes = document.querySelectorAll(
        `.slot-checkbox[data-classroom="${classroomId}"]:checked`
    );

    let grouped = {};

    checkboxes.forEach(cb => {

        const key = cb.dataset.day + cb.dataset.shift;

        if (!grouped[key]) {
            grouped[key] = [];
        }

        grouped[key].push(cb.value);
    });

    let result = [];

    Object.keys(grouped).forEach(key => {
        result.push(key + grouped[key].join(''));
    });

    document.getElementById(`hidden-schedule-${classroomId}`).value =
        result.join("\n");
}
</script>

@endsection