@extends('layouts.main')

@section('content')

<div class="container-fluid mt-4">

    <h4 class="mb-4">📄 Detalhes da Avaliação</h4>

    {{-- ================= CABEÇALHO ================= --}}
    <div class="card shadow-sm mb-3 no-print">
        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <strong>Avaliação</strong><br>
                    {{ $evaluation->name }}
                </div>

                <div class="col-md-3">
                    <strong>Turma</strong><br>
                    {{ $evaluation->classroom->name ?? '-' }} ({{ $evaluation->classroom->year ?? '-' }})
                </div>

                <div class="col-md-3">
                    <strong>Unidade</strong><br>
                    {{ $evaluation->unit ?? '-' }}ª
                </div>

                <div class="col-md-3">
                    <strong>Peso</strong><br>
                    {{ $evaluation->value }}
                </div>

                <div class="col-md-3 mt-2">
                    <strong>Data</strong><br>
                    {{ \Carbon\Carbon::parse($evaluation->date)->format('d/m/Y') ?? '-' }}
                </div>

                <div class="col-md-9 mt-2">
                    <strong>Descrição</strong><br>
                    {{ $evaluation->description ?? '-' }}
                </div>

            </div>

        </div>
    </div>

    {{-- ================= BOTÕES ================= --}}
    <div class="d-flex justify-content-end gap-2 mb-3 no-print">

        <button onclick="printPage()" class="btn btn-primary">
            🖨️ Imprimir
        </button>

        <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary">
            Voltar
        </a>

    </div>

    {{-- ================= TABELA ================= --}}
    <div class="card shadow-sm print-area">

        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle text-center">

                <thead class="table-light">
                    <tr>
                        <th class="text-start">Aluno</th>
                        <th style="width:150px;">Nota</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($students as $student)

                        @php
                            $grade = $grades->where('student_id', $student->id)->first();
                        @endphp

                        <tr>
                            <td class="text-start">{{ $student->name }}</td>
                            <td>{{ $grade->value ?? '-' }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="2">Nenhum aluno encontrado</td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ================= ESTILO ================= --}}
<style>
.no-print {
    display: block;
}

@media print {

    .no-print {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    table {
        font-size: 12px;
    }

    th {
        background: #f2f2f2 !important;
        color: #000 !important;
    }
}
</style>

{{-- ================= IMPRESSÃO PROFISSIONAL ================= --}}
<script>
function printPage() {

    const professor = "{{ auth()->user()->name }}";

    const evaluation = {
        name: "{{ $evaluation->name }}",
        classroom: "{{ $evaluation->classroom->name ?? '-' }}",
        year: "{{ $evaluation->classroom->year ?? '-' }}",
        unit: "{{ $evaluation->unit ?? '-' }}",
        value: "{{ $evaluation->value }}",
        date: "{{ \Carbon\Carbon::parse($evaluation->date)->format('d/m/Y') }}",
        description: "{{ $evaluation->description ?? '-' }}"
    };

    const table = document.querySelector('.print-area').outerHTML;

    const win = window.open('', '', 'width=900,height=700');

    win.document.write(`
        <html>
        <head>
            <title>Relatório de Avaliação</title>

            <style>

                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }

                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .info {
                    text-align: left;
                    margin-bottom: 15px;
                    font-size: 13px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }

                th {
                    background: #f2f2f2;
                    padding: 8px;
                }

                td {
                    border: 1px solid #ccc;
                    padding: 6px;
                }

            </style>
        </head>

        <body>

            <div class="header">
                <h3>Relatório de Avaliação</h3>
                <p><strong>Professor:</strong> ${professor}</p>
            </div>

            <div class="info">
                <strong>Avaliação:</strong> ${evaluation.name} <br>
                <strong>Turma:</strong> ${evaluation.classroom} (${evaluation.year}) <br>
                <strong>Unidade:</strong> ${evaluation.unit}ª <br>
                <strong>Peso:</strong> ${evaluation.value} <br>
                <strong>Data:</strong> ${evaluation.date} <br>
                <strong>Descrição:</strong> ${evaluation.description}
            </div>

            ${table}

        </body>
        </html>
    `);

    win.document.close();
    win.focus();
    win.print();
    win.close();
}
</script>

@endsection