@extends('layouts.public')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">

    <div class="col-md-4">

        <div class="card shadow-sm">

            <div class="card-header text-center">
                🎓 Login do Aluno
            </div>

            <div class="card-body">

                <form method="POST" action="/aluno/login">
                    @csrf

                    <div class="mb-3">
                        <label>Matrícula</label>
                        <input type="text" name="registration" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Senha</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Entrar
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection