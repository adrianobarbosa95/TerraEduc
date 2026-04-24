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

        .sidebar.collapsed {
            margin-left: -260px;
        }

        .sidebar .brand {
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
            border-left: 3px solid #60a5fa;
            padding-left: 25px;
        }

        .sidebar a.active {
            background-color: rgba(96,165,250,0.15);
            color: #ffffff;
            border-left: 3px solid #60a5fa;
            font-weight: 600;
        }

        /* TOPBAR */
        .topbar {
            margin-left: 260px;
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .topbar.expanded {
            margin-left: 0;
        }

        /* CONTENT */
        .content {
            margin-left: 260px;
            padding: 25px;
            transition: all 0.3s ease;
        }
 
        .content.expanded {
            margin-left: 0;
        }

        /* FOOTER */
        footer {
            margin-left: 260px;
            transition: all 0.3s ease;
        }

        footer.expanded {
            margin-left: 0;
        }

        /* TOGGLE BUTTON */
        #toggleBtn {
            border: none;
            background: transparent;
            font-size: 22px;
            cursor: pointer;
            margin-right: 10px;
        }

        /* CARDS */
        .card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="brand text-center">
            🎓 TerraEduc
        </div>

     
    <a href="{{ url('/home') }}" class="{{ request()->is('home') ? 'active' : '' }}">
    🏠 Início
</a>

<a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
    👨‍🎓 Alunos
</a>

<a href="{{ route('classrooms.index') }}" class="{{ request()->routeIs('classrooms.*') ? 'active' : '' }}">
    🏫 Turmas
</a>

<a href="{{ route('disciplines.index') }}" class="{{ request()->routeIs('disciplines.*') ? 'active' : '' }}">
    📚 Disciplinas
</a>

<a href="{{ route('evaluations.index') }}" class="{{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
    📝 Avaliações
</a>


<a href="{{ route('grades.create') }}" class="{{ request()->routeIs('grades.*') ? 'active' : '' }}">
    📈 Notas
</a>

<a href="#" class="">
    📊 Relatório
</a>

<a href="{{ route('schedules.index') }}" class="{{ request()->routeIs('schedules.*') ? 'active' : '' }}">
    🕒 Horário
</a>

<a href="#" class="">
    ⚙️ Configuração
</a>
    </div>

    <!-- TOPBAR -->
    <nav class="navbar topbar px-3" id="topbar">
        <div class="container-fluid d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center">
                <button id="toggleBtn">⮜</button>
                <span class="fw-semibold text-secondary">Sistema Acadêmico</span>
            </div>

            <div>
                <span class="me-3 text-muted">{{Auth::user()->name}}</span>
                <button class="btn btn-sm btn-outline-secondary "><a class="text-muted" href="{{ route('logout') }}" style="text-decoration: none;">Sair</a></button>
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
</html>