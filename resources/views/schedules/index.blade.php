@extends('layouts.main')

@section('content')
@php
    $hoje = \Carbon\Carbon::now();

    $diaAtualTexto = $hoje->dayOfWeek;

    $diaAtual = $hoje->format('d/m/Y') . ' - ' . match($hoje->dayOfWeek) {
        0 => 'Domingo',
        1 => 'Segunda',
        2 => 'Terça',
        3 => 'Quarta',
        4 => 'Quinta',
        5 => 'Sexta',
        6 => 'Sábado',
    };
@endphp
<style>
    .dia-hoje {
        
    color: #6e0000 !important; /* laranja elegante */
    /* font-weight: bold; */
}

/* na impressão não muda nada */
@media print {
    .dia-hoje {
        color: inherit !important;
        font-weight: bold !important;
    }
}
    body {
        background: #f8f9fa;
        font-size: 14px;
    }

    h4 {
        font-weight: 600;
    }

    table {
        font-size: 13px;
    }

    td, th {
        padding: 8px !important;
        vertical-align: middle;
    }

    .box-aula {
        /* padding: 6px; */
        /* margin-bottom: 4px; */
        /* border: 1px solid #ddd; */
        border-radius: 6px;
        background: #ffffff;
        font-size: 12px;
    }

    .print-btn {
        margin-bottom: 15px;
    }

    /* 🔹 IMPRESSÃO */
    @media print {

    body * {
        visibility: hidden;
    }

    .print-area, .print-area * {
        visibility: visible;
    }

    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    .print-btn {
        display: none;
    }

    /* 🔽 Redução leve */
    /* body {
        font-size: 11px;
    } */

    table {
        font-size: 10px;
        border-collapse: collapse;
    }

    td, th {
        padding: 4px !important;
        border: 1px solid #000 !important;
    }

    /* 🔽 Mantém cores na impressão */
    th {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* 🔵 TURNO */
    th.bg-dark {
        background-color: #000 !important;
        color: #fff !important;
    }

    /* 🔘 HEADER DIAS */
    .table-secondary th {
        background-color: #d9d9d9 !important;
        color: #000 !important;
    }

    /* 🔽 Células alternadas (efeito leve) */
    tr:nth-child(even) td {
        background-color: #f2f2f2 !important;
    }

    /* 🔽 Caixinha das aulas */
    .box-aula {
        font-size: 9px;
        /* padding: 3px; */
        margin-bottom: 2px;
        /* border: 1px solid #555; */
        background: #fff;
    }

    /* 🔽 Evita quebra */
    table, tr, td {
        page-break-inside: avoid;
    }

    /* 🔽 Ajuste fino */
    .print-area {
        transform: scale(0.95);
        transform-origin: top left;
        width: 105%;
    }
}
</style>

<div class="container mt-4">

    {{-- 🔹 Botão --}}
    <div class="text-end print-btn">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Imprimir
        </button>
    </div>

    {{-- 🔹 ÁREA DE IMPRESSÃO --}}
    <div class="print-area">

       <h4 class="mb-1  ">📅 Grade de Horários</h4>
<br>
<h5 class="text-center mb-4">
    Professor: {{ auth()->user()->name ?? '---' }}
</h5>
  <p class="text-center mb-3">
            <strong></strong> {{ $diaAtual }}
        </p>
        {{ $diaAtualTexto++ ? '' : ''}}
        @foreach($timeSlots as $shift => $horarios)

            <table class="table table-bordered text-center mb-5">

                {{-- TURNO --}}
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

                  @foreach($days as $dayKey => $dayName)
    <th class="{{ $dayKey == $diaAtualTexto ? 'dia-hoje' : '' }}">
        {{ $dayName }}     
    </th>
@endforeach
                </tr>

                {{-- LINHAS --}}
                @foreach($horarios as $slot => $hora)

                    <tr>

                        <td><strong>{{ $hora }}</strong></td>

                        @foreach($days as $dayKey => $dayName)

                            <td>

                                @if(!empty($grid[$shift][$dayKey][$slot]))

                                    @foreach($grid[$shift][$dayKey][$slot] as $item)

                                        <div class="box-aula">
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
                                    <span class="text-muted"></span>
                                @endif

                            </td>

                        @endforeach

                    </tr>

                @endforeach

            </table>

        @endforeach

    </div>

</div>

@endsection