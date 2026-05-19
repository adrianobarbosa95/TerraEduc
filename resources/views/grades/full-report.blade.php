@extends('layouts.main')

@section('content')

<style>

    body{
        background:#f4f6f9;
    }

    .page-title{
        font-size:28px;
        font-weight:700;
        color:#1e293b;
        margin-bottom:5px;
    }

    .page-subtitle{
        color:#64748b;
        margin-bottom:25px;
    }

    .report-toolbar{
        display:flex;
        gap:10px;
        margin-bottom:25px;
        flex-wrap:wrap;
    }

    .report-card{
        border:none;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 2px 12px rgba(0,0,0,0.05);
        margin-bottom:25px;
        background:white;
    }

    .report-header{
        background:linear-gradient(135deg,#2563eb,#1d4ed8);
        color:white;
        padding:14px 18px;
    }

    .report-header h3{
        margin:0;
        font-size:19px;
        font-weight:700;
    }

    .report-meta{
        margin-top:5px;
        font-size:12px;
        opacity:0.95;
    }

    .discipline-section{
        margin-top:18px;
    }

    .discipline-title{
        background:#eff6ff;
        border-left:5px solid #2563eb;
        padding:10px 14px;
        border-radius:8px;
        font-size:16px;
        font-weight:700;
        color:#1e3a8a;
        margin-bottom:10px;
    }

    .table-report{
        font-size:11px;
        margin-bottom:0;
    }

    .table-report thead th{
        background:#0f172a;
        color:white;
        text-align:center;
        vertical-align:middle;
        padding:6px;
        border-color:#1e293b;
        white-space:nowrap;
    }

    .table-report tbody td{
        vertical-align:middle;
        padding:4px;
    }

    .student-col{
        min-width:220px;
        font-weight:600;
        background:#fafafa;
    }

    .grade-col{
        text-align:center;
        width:60px;
    }

    .evaluation-name{
        font-size:11px;
        font-weight:700;
    }

    .evaluation-value{
        font-size:9px;
        opacity:0.85;
        margin-top:2px;
    }

    .total-col{
        width:70px;
        text-align:center;
        font-weight:700;
        font-size:12px;
    }

    .approved{
        background:#dcfce7 !important;
        color:#166534 !important;
    }

    .recovery{
        background:#fef3c7 !important;
        color:#92400e !important;
    }

    .failed{
        background:#fee2e2 !important;
        color:#991b1b !important;
    }

    .legend{
        display:flex;
        gap:15px;
        flex-wrap:wrap;
        margin-top:12px;
        margin-bottom:10px;
    }

    .legend-item{
        display:flex;
        align-items:center;
        gap:6px;
        font-size:11px;
    }

    .legend-color{
        width:14px;
        height:14px;
        border-radius:4px;
    }

    .footer-info{
        margin-top:15px;
        font-size:11px;
        color:#64748b;
        text-align:right;
    }

    .table-responsive{
        border-radius:12px;
        overflow:hidden;
    }

    .table-report tbody tr:hover{
        background:#f8fafc;
    }

    @media print {

        body{
            background:white !important;
        }

        .navbar,
        .sidebar,
        footer,
        .report-toolbar,
        .no-print{
            display:none !important;
        }

        .container{
            width:100% !important;
            max-width:100% !important;
            padding:0 !important;
        }

        .report-card{
            box-shadow:none !important;
            border:none !important;
            margin-bottom:12px !important;
            break-inside:avoid;
        }

        .report-header{
            background:#1d4ed8 !important;
            color:white !important;
            -webkit-print-color-adjust:exact;
            print-color-adjust:exact;
            padding:10px 14px !important;
        }

        .approved,
        .recovery,
        .failed{
            -webkit-print-color-adjust:exact;
            print-color-adjust:exact;
        }

        .table-report{
            font-size:9px !important;
        }

        .table-report th,
        .table-report td{
            padding:3px !important;
        }

        .student-col{
            min-width:150px !important;
        }

        .discipline-title{
            padding:6px 10px !important;
            font-size:13px !important;
            margin-bottom:6px !important;
        }

        .report-header h3{
            font-size:16px !important;
        }

        .report-meta{
            font-size:10px !important;
        }

        .footer-info{
            font-size:9px !important;
            margin-top:8px !important;
        }

        table{
            page-break-inside:auto;
        }

        tr{
            page-break-inside:avoid;
            page-break-after:auto;
        }

        thead{
            display:table-header-group;
        }

        .discipline-section{
            break-inside:avoid;
            margin-top:10px !important;
        }

        @page{
            size:A4 portrait;
            margin:10mm;
        }
    }

</style>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

        <div>

            <div class="page-title">
                📘 Relatório Geral de Notas
            </div>

            <div class="page-subtitle">
                Visualização completa das turmas e disciplinas
            </div>

        </div>

    </div>

    <div class="row mb-4 no-print">

        <div class="col-md-3">

            <label class="form-label fw-bold">
                Unidade
            </label>

            <select id="unitFilter" class="form-control">

                <option value="1" {{ request('unit') == 1 ? 'selected' : '' }}>
                    Unidade 1
                </option>

                <option value="2" {{ request('unit') == 2 ? 'selected' : '' }}>
                    Unidade 2
                </option>

                <option value="3" {{ request('unit') == 3 ? 'selected' : '' }}>
                    Unidade 3
                </option>

            </select>

        </div>

        <div class="col-md-2 d-flex align-items-end">

            <button class="btn btn-primary w-100" onclick="filterUnit()">
                Filtrar
            </button>

        </div>

    </div>

    <div class="report-toolbar">

        <button onclick="window.print()" class="btn btn-dark">
            🖨️ Imprimir Relatório
        </button>

        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            ← Voltar
        </a>

    </div>

    @foreach($report as $item)

        <div class="report-card">

            <div class="report-header">

                <h3>
                    {{ $item['classroom']->name }}
                    ({{ $item['classroom']->year }})
                </h3>

                <div class="report-meta">

                    <strong>Professor:</strong>
                    {{ auth()->user()->name }}

                    &nbsp;&nbsp;|&nbsp;&nbsp;

                    <strong>Unidade:</strong>
                    {{ $unit }}

                    &nbsp;&nbsp;|&nbsp;&nbsp;

                    <strong>Emitido em:</strong>
                    {{ now()->format('d/m/Y H:i') }}

                </div>

            </div>

            <div class="card-body p-3">

                <div class="legend">

                    <div class="legend-item">
                        <div class="legend-color approved"></div>
                        Aprovado (≥ 5.0)
                    </div>

                    <div class="legend-item">
                        <div class="legend-color recovery"></div>
                        Recuperação (≥ 3.0)
                    </div>

                    <div class="legend-item">
                        <div class="legend-color failed"></div>
                        Reprovado (< 3.0)
                    </div>

                </div>

                @foreach($item['disciplines'] as $disc)

                    <div class="discipline-section">

                        <div class="discipline-title">
                            📚 {{ $disc['discipline']->name }}
                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered table-report">

                                <thead>

                                    <tr>

                                        <th class="student-col">
                                            Aluno
                                        </th>

                                        @foreach($disc['evaluations'] as $ev)

                                            <th>

                                                <div class="evaluation-name">
                                                    {{ $ev->name }}
                                                </div>

                                                <div class="evaluation-value">
                                                    Vale {{ number_format($ev->value,1) }}
                                                </div>

                                            </th>

                                        @endforeach

                                        <th class="total-col">
                                            Total
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($disc['students'] as $student)

                                        @php
                                            $total = 0;
                                        @endphp

                                        <tr>

                                            <td class="student-col">
                                                {{ $student->name }}
                                            </td>

                                            @foreach($disc['evaluations'] as $ev)

                                                @php

                                                    $grade = $disc['grades']
                                                        ->where('student_id', $student->id)
                                                        ->where('evaluation_id', $ev->id)
                                                        ->first();

                                                    $value = $grade->value ?? 0;

                                                    $total += $value;

                                                @endphp

                                                <td class="grade-col">
                                                    {{ number_format($value,1) }}
                                                </td>

                                            @endforeach

                                            <td class="
                                                total-col

                                                @if($total >= 5)
                                                    approved
                                                @elseif($total >= 3)
                                                    recovery
                                                @else
                                                    failed
                                                @endif
                                            ">

                                                {{ number_format($total,1) }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @endforeach

                <div class="footer-info">
                    Relatório gerado automaticamente pelo sistema TerraEduc
                </div>

            </div>

        </div>

    @endforeach

</div>

<script>

function filterUnit() {

    const unit = document.getElementById('unitFilter').value;

    window.location.href =
        `/grades/full-report?unit=${unit}`;
}

</script>

@endsection