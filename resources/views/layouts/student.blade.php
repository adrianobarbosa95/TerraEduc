<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerraEduc</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #0e2747, #163a63);
            padding-top: 20px;
            color: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed { margin-left: -260px; }

        .sidebar .brand {
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            padding: 12px 20px;
            text-decoration: none;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
            border-left: 3px solid #60a5fa;
            padding-left: 25px;
        }

        .topbar {
            margin-left: 260px;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        .topbar.expanded { margin-left: 0; }

        .content {
            margin-left: 260px;
            padding: 25px;
        }

        .content.expanded { margin-left: 0; }

        footer {
            margin-left: 260px;
        }

        footer.expanded { margin-left: 0; }

        #toggleBtn {
            border: none;
            background: transparent;
            font-size: 22px;
        }

        .card {
            border: none;
            border-radius: 12px;
        }
    </style>
</head>

<body>

@php
    $user = Auth::guard('students')->check()
        ? Auth::guard('students')->user()
        : Auth::user();
@endphp

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="brand text-center">
        🎓 TerraEduc
    </div>

    <a href="{{ url('/aluno/dashboard') }}">🏠 Início</a>

</div>

<!-- TOPBAR -->
<nav class="navbar topbar px-3" id="topbar">

    <div class="container-fluid d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <button id="toggleBtn">⮜</button>
            <span class="fw-semibold text-secondary ms-2">Sistema Acadêmico</span>
        </div>

        <div>
            <span class="me-3 text-muted">
                {{ $user->name ?? 'Usuário' }}
            </span>

           <form method="POST" action="{{ route('logout-student') }}" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-sm btn-outline-secondary">
        Sair
    </button>
</form>
        </div>

    </div>

</nav>

<!-- CONTENT -->
<div class="content" id="content">
    @yield('content')
</div>

<!-- FOOTER -->
<footer class="text-center mt-4 text-muted" id="footer">
    <hr>
    <small>© {{ date('Y') }} TerraEduc</small>
</footer>

<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const topbar = document.getElementById('topbar');
    const footer = document.getElementById('footer');
    const toggleBtn = document.getElementById('toggleBtn');

    let isOpen = true;

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('expanded');
        topbar.classList.toggle('expanded');
        footer.classList.toggle('expanded');

        isOpen = !isOpen;
        toggleBtn.innerHTML = isOpen ? '⮜' : '⮞';
    });
</script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>