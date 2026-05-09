<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{ $professor->name }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        /* CAPA */
        .cover {
            height: 250px;
            background: linear-gradient(135deg, #0e2747, #163a63);
        }

        /* CARD */
        .profile-card {
            margin-top: -100px;
            border-radius: 16px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #fff;
        }

        .name {
            font-weight: 600;
            color: #0e2747;
        }

        .card {
            border-radius: 16px;
        }

        .links a {
            display: block;
            text-decoration: none;
            color: #163a63;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .links a:hover {
            color: #60a5fa;
            padding-left: 5px;
        }

        .section-title {
            color: #0e2747;
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- CAPA -->
<div class="cover"></div>

<div class="container">

    <!-- PERFIL -->
    <div class="card shadow profile-card text-center p-4">

        <img src="{{ $professor->photo ? asset('storage/'.$professor->photo) : 'https://via.placeholder.com/150' }}"
             class="rounded-circle profile-img mx-auto">

        <h2 class="mt-3 name">{{ $professor->name }}</h2>

        {{-- opcional remover email --}}
        <p class="text-muted">{{ $professor->email }}</p>

    </div>

    <!-- CONTEÚDO -->
    <div class="row mt-4">

        <!-- BIO -->
        <div class="col-md-8">
            <div class="card shadow-sm p-4">

                <h5 class="section-title">Sobre</h5>

                <p style="white-space: pre-line;" class="text-muted">
                    {{ $professor->bio ?? 'Este professor ainda não adicionou uma bio.' }}
                </p>

            </div>
        </div>

        <!-- LINKS -->
        <div class="col-md-4">
            <div class="card shadow-sm p-4">

                <h6 class="section-title">Links</h6>

                <div class="links">

                    @if($professor->lattes)
                        <a href="{{ $professor->lattes }}" target="_blank">🎓 Lattes</a>
                    @endif

                    @if($professor->github)
                        <a href="{{ $professor->github }}" target="_blank">💻 GitHub</a>
                    @endif

                    @if($professor->linkedin)
                        <a href="{{ $professor->linkedin }}" target="_blank">🔗 LinkedIn</a>
                    @endif

                    @if($professor->instagram)
                        <a href="{{ $professor->instagram }}" target="_blank">📸 Instagram</a>
                    @endif

                    @if($professor->website)
                        <a href="{{ $professor->website }}" target="_blank">🌐 Site</a>
                    @endif

                    @if(
                        !$professor->lattes &&
                        !$professor->github &&
                        !$professor->linkedin &&
                        !$professor->instagram &&
                        !$professor->website
                    )
                        <p class="text-muted small">Nenhum link informado.</p>
                    @endif

                </div>

            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="text-center mt-5 mb-3 text-muted">
        <small>© {{ date('Y') }} TerraEduc</small>
    </div>

</div>

</body>
</html>