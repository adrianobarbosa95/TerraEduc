<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerraEduc</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #0e2747, #163a63);
            padding-top: 20px;
            color: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .sidebar.collapsed { margin-left: -220px; }
        .sidebar.show { margin-left: 0; }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
            margin-bottom: 15px;
        }

        #closeSidebar {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .sidebar .brand {
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            padding: 10px 15px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
            border-left: 3px solid #60a5fa;
            padding-left: 20px;
        }

        /* TOPBAR */
        .topbar {
            margin-left: 220px;
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .topbar.expanded { margin-left: 0; }

        /* CONTENT */
        .content {
            margin-left: 220px;
            padding: 25px;
            transition: all 0.3s ease;
        }

        .content.expanded { margin-left: 0; }

        /* FOOTER */
        footer {
            margin-left: 220px;
            transition: all 0.3s ease;
        }

        footer.expanded { margin-left: 0; }

        #toggleBtn {
            border: none;
            background: transparent;
            font-size: 22px;
            cursor: pointer;
            margin-right: 10px;
            z-index: 1100;
        }

        /* MOBILE */
        @media (max-width: 992px) {

            .sidebar {
                width: 70%;
                margin-left: -100%;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .topbar,
            .content,
            footer {
                margin-left: 0 !important;
            }

            /* 🔥 ESCONDE O TÍTULO */
            .system-title {
                display: none;
            }
        }

        /* OVERLAY */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;
            z-index: 1040;
        }

        #overlay.show {
            display: block;
        }
    </style>
</head>

<body>

<div id="overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <span>🎓 TerraEduc</span>
        <button id="closeSidebar">✖</button>
    </div>

    <a href="{{ url('/home') }}">🏠 Início</a>
    <a href="{{ route('students.index') }}">👨‍🎓 Alunos</a>
    <a href="{{ route('classrooms.index') }}">🏫 Turmas</a>
    <a href="{{ route('disciplines.index') }}">📚 Disciplinas</a>
    <a href="{{ route('evaluations.index') }}">📝 Avaliações</a>
    <a href="{{ route('grades.create') }}">📈 Notas</a>
    <a href="#">📊 Relatório</a>
    <a href="{{ route('schedules.index') }}">🕒 Horário</a>
    <a href="#">⚙️ Configuração</a>

</div>

<!-- TOPBAR -->
<nav class="navbar topbar px-3" id="topbar">

    @php
        $user = Auth::user() ?? Auth::guard('students')->user();
    @endphp

    <div class="container-fluid d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <button id="toggleBtn">⮜</button>
            <span class="system-title fw-semibold text-secondary">
                Sistema Acadêmico
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="text-muted">
                {{ $user->name ?? 'Usuário' }}
            </span>

            <a href="{{ route('tutorial') }}" class="btn btn-sm btn-outline-info">❓</a>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-secondary">Sair</a>
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
    const overlay = document.getElementById('overlay');
    const closeSidebar = document.getElementById('closeSidebar');

    function isMobile() {
        return window.innerWidth <= 992;
    }

    function updateIcon() {
        toggleBtn.innerHTML = sidebar.classList.contains('collapsed') ? '⮞' : '⮜';
    }

    toggleBtn.addEventListener('click', () => {
        if (isMobile()) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        } else {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
            topbar.classList.toggle('expanded');
            footer.classList.toggle('expanded');
            updateIcon();
        }
    });

    closeSidebar.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            if (isMobile()) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    });

    window.addEventListener('resize', () => {
        if (!isMobile()) {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
    });
</script>

</body>
</html>