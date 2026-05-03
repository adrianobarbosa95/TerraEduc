<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TerraEduc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        html { scroll-behavior: smooth; }

        /* 🔥 BACKGROUND ANIMADO */
        body {
            margin: 0;
            background: linear-gradient(-45deg, #eef2f7, #e3e9f3, #f8f9fc, #e9f0ff);
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
            overflow-x: hidden;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }

        .hero h1 {
            font-size: 4rem;
            animation: fadeUp 1s ease;
        }

        .hero p {
            font-size: 1.3rem;
            animation: fadeUp 1.2s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* BOTÃO SCROLL */
        .scroll-btn {
            position: absolute;
            bottom: 35px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 30px;
            color: #666;
            animation: bounce 1.5s infinite;
        }

        @keyframes bounce {
            0%,100% { transform: translate(-50%,0); }
            50% { transform: translate(-50%,12px); }
        }

        /* SEÇÕES */
        .section-full {
            min-height: 100vh;
            display: flex;
            align-items: center;
            opacity: 0;
            transform: translateY(60px);
            transition: all 0.7s ease;
        }

        .section-full.show {
            opacity: 1;
            transform: translateY(0);
        }

        .bg-soft {
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(10px);
        }

        /* 🔥 MOCKUP CONTAINER */
        .mockup-container {
            position: relative;
            display: inline-block;
            overflow: hidden;
            border-radius: 14px;
        }

        .mockup-container::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.15);
            opacity: 0;
            transition: 0.4s;
        }

        .mockup {
            max-width: 100%;
            border-radius: 14px;
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.6s ease;
            filter: brightness(0.95);
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        .section-full.show .mockup {
            transform: scale(1);
            opacity: 1;
        }

        /* 🔥 HOVER PREMIUM */
        .mockup-container:hover .mockup {
            transform: scale(1.08);
            filter: brightness(1.1) contrast(1.1) saturate(1.05);
            box-shadow: 0 35px 90px rgba(0,0,0,0.35);
        }

        .mockup-container:hover::after {
            opacity: 1;
        }

        /* NAV DOTS */
        .nav-dots {
            position: fixed;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            z-index: 999;
        }

        .nav-dots a {
            display: block;
            width: 10px;
            height: 10px;
            background: #ccc;
            border-radius: 50%;
            margin: 10px 0;
            transition: 0.3s;
        }

        .nav-dots a.active {
            background: #0d6efd;
            transform: scale(1.5);
        }

        /* BOTÃO TOPO */
        .back-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #0d6efd;
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .back-top.show {
            display: flex;
        }

    </style>
</head>

<body>

<!-- NAV -->
<div class="nav-dots">
    <a href="#hero"></a>
    <a href="#modulos"></a>
    <a href="#professor"></a>
    <a href="#aluno"></a>
    <a href="#gestao"></a>
</div>

<!-- HERO -->
<section id="hero" class="hero">

    <h1 class="fw-bold">TerraEduc</h1>
    <p class="text-muted">Plataforma moderna para gestão educacional</p>

    <div class="d-flex gap-3 mt-4">  
        <a href="{{ route('login') }}" class="btn btn-primary px-4">Acessar como Professor</a>
        <a href="{{ route('login-student') }}" class="btn btn-success px-4">Acessar como Aluno</a>
    </div>

    <a href="#modulos" class="scroll-btn">↓</a>

</section>

<!-- MODULOS -->
<section id="modulos" class="section-full bg-soft">

    <div class="container text-center">

        <h3 class="mb-3">Tudo em um só lugar</h3>
        <p class="text-muted mb-5">Sistema completo e integrado</p>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <h5>Professor</h5>
                    <p class="text-muted">Gerencie aulas e avaliações</p>
                    <a href="#professor" class="btn btn-outline-primary btn-sm">Ver</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <h5>Aluno</h5>
                    <p class="text-muted">Acompanhe seu desempenho</p>
                    <a href="#aluno" class="btn btn-outline-success btn-sm">Ver</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <h5>Gestão</h5>
                    <p class="text-muted">Controle acadêmico completo</p>
                    <a href="#gestao" class="btn btn-outline-dark btn-sm">Ver</a>
                </div>
            </div>

        </div>

    </div>

    <a href="#professor" class="scroll-btn">↓</a>

</section>

<!-- PROFESSOR -->
<section id="professor" class="section-full">

    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6">
                <h3>Professor</h3>
                <p class="text-muted">Organize suas aulas, avaliações e acompanhe alunos.</p>
                <p class="text-muted">Tudo de forma simples e eficiente.</p>
            </div>

            <div class="col-md-6 text-center">
                <div class="mockup-container">
                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1000" class="mockup">
                </div>
            </div>

        </div>
    </div>

    <a href="#aluno" class="scroll-btn">↓</a>

</section>

<!-- ALUNO -->
<section id="aluno" class="section-full bg-soft">

    <div class="container">
        <div class="row align-items-center flex-md-row-reverse">

            <div class="col-md-6">
                <h3>Aluno</h3>
                <p class="text-muted">Veja suas notas e progresso.</p>
                <p class="text-muted">Entenda seu desempenho facilmente.</p>
            </div>

            <div class="col-md-6 text-center">
                <div class="mockup-container">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1000" class="mockup">
                </div>
            </div>

        </div>
    </div>

    <a href="#gestao" class="scroll-btn">↓</a>

</section>

<!-- GESTÃO -->
<section id="gestao" class="section-full">

    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6">
                <h3>Gestão Acadêmica</h3>
                <p class="text-muted">Gerencie toda a instituição.</p>
                <p class="text-muted">Dados organizados para decisões rápidas.</p>
            </div>

            <div class="col-md-6 text-center">
                <div class="mockup-container">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1000" class="mockup">
                </div>
            </div>

        </div>
        <a href="{{ route('tutorial') }}" 
   class="btn btn-outline-secondary"
   style="transition:0.2s;"
   onmouseover="this.style.transform='scale(1.05)'"
   onmouseout="this.style.transform='scale(1)'">
    📘 Como funciona
</a>
    </div>

</section>

<button class="back-top" onclick="scrollToTop()">↑</button>

<script>

    const sections = document.querySelectorAll('.section-full');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, { threshold: 0.3 });

    sections.forEach(sec => observer.observe(sec));

    const btnTop = document.querySelector('.back-top');

    window.addEventListener('scroll', () => {

        btnTop.classList.toggle('show', window.scrollY > 300);

        document.querySelectorAll('.nav-dots a').forEach(link => {
            const section = document.querySelector(link.getAttribute('href'));
            const rect = section.getBoundingClientRect();

            link.classList.toggle('active',
                rect.top <= 200 && rect.bottom >= 200
            );
        });

    });

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

</script>

</body>
</html>