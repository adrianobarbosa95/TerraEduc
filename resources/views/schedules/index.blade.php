@extends('layouts.main')

@section('content')

<div class="container mt-4">

    <h4 class="mb-4">📅 Grade de Horários</h4>

    @foreach($timeSlots as $shift => $horarios)

        <table class="table table-bordered text-center mb-5">

            {{-- 🔵 TURNO --}}
            <tr>
                <th colspan="{{ count($days) + 1 }}" class="bg-dark text-white">
                    @if($shift == 'M') Matutino
                    @elseif($shift == 'T') Vespertino
                    @else Noturno
                    @endif
                </th>
            </tr>

            {{-- HEADER --}}
            <tr class="table-secondary">
                <th>Horário</th>

                @foreach($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>

            {{-- LINHAS --}}
            @foreach($horarios as $slot => $hora)

                <tr>

                    <td>{{ $hora }}</td>

                    @foreach($days as $dayKey => $dayName)

                        <td>

                            @if(!empty($grid[$shift][$dayKey][$slot]))

                                @foreach($grid[$shift][$dayKey][$slot] as $item)

                                    <div class="p-2 mb-1 border rounded bg-light">

                                        <strong>
                                            {{ $item->discipline->name ?? '' }}
                                        </strong>

                                         -

                                        <small>
                                             {{ $item->classroom->name ?? '' }}
                                        </small>

                                    </div>

                                @endforeach

                            @else
                                <span class="text-muted">—</span>
                            @endif

                        </td>

                    @endforeach

                </tr>

            @endforeach

        </table>

    @endforeach

</div>

@endsection