@extends('layouts.student')

@section('content')

<div class="container mt-4">

    @php
        use App\Models\ClassDisciplineSchedule;

        // 🔥 CORREÇÃO PRINCIPAL: consulta isolada por turma + disciplina
        $schedules = ClassDisciplineSchedule::where([
            'classroom_id' => $classroom->id,
            'discipline_id' => $discipline->id
        ])->get();

        $diasSemana = [
            2 => 'Segunda',
            3 => 'Terça',
            4 => 'Quarta',
            5 => 'Quinta',
            6 => 'Sexta',
        ];

        $horarios = [
            'M' => [
                1 => '07:40 - 08:30',
                2 => '08:30 - 09:20',
                3 => '09:20 - 10:10',
                4 => '10:20 - 11:10',
                5 => '11:10 - 12:00',
                6 => '12:00 - 12:50',
            ],
            'T' => [
                1 => '13:10 - 14:00',
                2 => '14:00 - 14:50',
                3 => '14:50 - 15:40',
                4 => '15:40 - 16:30',
                5 => '16:30 - 17:20',
                6 => '17:20 - 18:10',
            ],
            'N' => [
                1 => '18:30 - 19:20',
                2 => '19:20 - 20:10',
                3 => '20:10 - 21:00',
                4 => '21:00 - 21:50',
                5 => '21:50 - 22:40',
            ],
        ];
    @endphp

    <!-- HEADER -->
    <div class="mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div class="fw-semibold fs-4">
                {{ $discipline->name }}
            </div>

            <div class="text-muted small text-end">

                <div>
                    Prof. {{ $discipline->user->name ?? '-' }} • 
                    {{ $classroom->name ?? '-' }} • 
                    {{ $classroom->modality ?? '-' }} • 
                    {{ $classroom->year ?? '-' }}.{{ $classroom->period ?? '-' }} • 
                    {{ $classroom->units ?? '-' }} unidades
                </div>

                <div>
                    @forelse($schedules as $schedule)

                        @php
                            $slots = is_string($schedule->slots)
                                ? str_split($schedule->slots)
                                : [];

                            $listaHorarios = collect($slots)
                                ->map(fn($slot) => $horarios[$schedule->shift][$slot] ?? null)
                                ->filter()
                                ->values()
                                ->toArray();
                        @endphp

                        {{ $diasSemana[$schedule->day] ?? '-' }}

                        @if(count($listaHorarios))
                            ({{ implode(', ', $listaHorarios) }})
                        @endif

                        @if(!$loop->last) • @endif

                    @empty
                        <span class="text-muted">Horário não cadastrado</span>
                    @endforelse
                </div>

            </div>

        </div>

    </div>

    <!-- TÍTULO -->
    <div class="mb-2 text-center">
        <h5 class="fw-semibold">Plano de Aulas</h5>
        <small class="text-muted">
            Aulas previstas conforme calendário e horário da disciplina
        </small>
    </div>

    <!-- TABELA -->
    <div class="card border-0 shadow-sm p-3 rounded-3">

        <div class="table-responsive">

            <table class="table table-sm table-striped align-middle">

                <thead>
                    <tr>
                        <th># Aula</th>
                        <th>Data</th>
                        <th>Conteúdo</th>
                        <th>Slide</th>
                        <th>Atividade / Material</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($classes as $class)

                    @php
                        $plan = $plans[$class['date']] ?? null;
                    @endphp

                    <tr>
                        <td>{{ $class['number'] }}</td>

                        <td>
                            {{ $class['formatted'] }}
                            <small class="text-muted">
                                ({{ $class['day'] }})
                            </small>
                        </td>

                        <td>{{ $plan->content ?? '' }}</td>

                        <td>
                            @if(!empty($plan->slide))
                                <a href="{{ $plan->slide }}" target="_blank">
                                    {{ $plan->slide }}
                                </a>
                            @endif
                        </td>

                        <td>{{ $plan->activity ?? '' }}</td>
                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection