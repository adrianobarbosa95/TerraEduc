<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TerraEduc</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0e2747, #163a63);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-login {
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .logo {
            font-weight: bold;
            font-size: 22px;
            color: #0e2747;
        }
    </style>
</head>
<body>

<div class="card card-login p-4">

    <div class="text-center mb-3">
        <div class="logo">TerraEduc🎓</div>
        <small class="text-muted">Sistema Acadêmico</small>
    </div>

    <!-- STATUS -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- ERROS -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        </div>

        <!-- SENHA -->
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <!-- LEMBRAR -->
        <div class="form-check mb-3">
            <input type="checkbox" name="remember" class="form-check-input">
            <label class="form-check-label">Lembrar-me</label>
        </div>

        <!-- BOTÃO -->
        <button type="submit" class="btn btn-primary w-100">
            Entrar
        </button>

        <!-- LINKS -->
        <div class="text-center mt-3">

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="d-block mb-2">
                    Esqueceu a senha?
                </a>
            @endif

            <!-- 🔥 CADASTRO -->
            @if (Route::has('register'))
                <a href="{{ route('register') }}">
                    Criar uma conta
                </a>
            @endif

        </div>

    </form>

</div>

</body>
</html>