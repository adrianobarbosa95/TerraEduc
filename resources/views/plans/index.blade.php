@extends('layouts.main')

@section('content')

<div class="container mt-4">

    <!-- HEADER -->
    <div class="mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <!-- ESQUERDA -->
            <div class="fw-semibold fs-4">
                {{ $discipline->name }}
            </div>

            <!-- DIREITA -->
            <div class="text-muted small text-end">

                <div>
                    Prof. {{ $discipline->user->name ?? '-' }} • 
                    {{ $classroom->name ?? '-' }} • 
                    {{ $classroom->modality ?? '-' }} • 
                    {{ $classroom->year ?? '-' }}.{{ $classroom->period ?? '-' }} • 
                    {{ $classroom->units ?? '-' }} unidades
                </div>

                <div>

                    @php
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

                    @forelse(($schedules ?? []) as $schedule)

                        @php
                            $slots = $schedule->slots ? str_split($schedule->slots) : [];

                            $listaHorarios = [];

                            foreach ($slots as $slot) {
                                $listaHorarios[] = $horarios[$schedule->shift][$slot] ?? null;
                            }

                            $listaHorarios = array_filter($listaHorarios);
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
    <div class="mb-3 text-center">
        <h5 class="fw-semibold">Plano de Aulas</h5>
        <small class="text-muted">Preencha o conteúdo de cada aula</small>
    </div>

    <!-- FORM -->
    <form action="{{ route('plans.store') }}" method="POST">
        @csrf

        <input type="hidden" name="discipline_id" value="{{ $discipline->id }}">
        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

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
                                    ({{ ucfirst($class['day']) }})
                                </small>
                            </td>

                            <td>
                                <textarea name="plans[{{ $class['date'] }}][content]"
                                          class="form-control form-control-sm"
                                          rows="1">{{ $plan->content ?? '' }}</textarea>
                            </td>

                            <td>
                                <input type="text"
                                       name="plans[{{ $class['date'] }}][slide]"
                                       value="{{ $plan->slide ?? '' }}"
                                       class="form-control form-control-sm">
                            </td>

                            <td>
                                <textarea name="plans[{{ $class['date'] }}][activity]"
                                          class="form-control form-control-sm"
                                          rows="1">{{ $plan->activity ?? '' }}</textarea>
                            </td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <!-- BOTÃO -->
        <div class="mt-3 text-end">
            <button type="submit" class="btn btn-primary">
                Salvar Plano
            </button>
        </div>

    </form>

</div>

@endsection