@extends('layouts.main')

@section('content')

<div class="container mt-4">

    <h4>📚 Histórico do Aluno</h4>

    <p><strong>{{ $student->name }}</strong></p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Turma</th>
                <th>Ano</th>
                <th>Entrada</th>
                <th>Saída</th>
            </tr>
        </thead>
        <tbody>
            @foreach($student->history as $h)
                <tr>
                    <td>{{ $h->classroom->name }}</td>
                    <td>{{ $h->year }}</td>
                    <td>{{ $h->entered_at ? \Carbon\Carbon::parse($h->entered_at)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $h->left_at ? \Carbon\Carbon::parse($h->left_at)->format('d/m/Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection