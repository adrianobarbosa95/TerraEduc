@extends('layouts.main') {{-- ajuste conforme seu layout --}}

@section('content')

<div class="container-fluid">

    <h4 class="mb-4">Dashboard</h4>

    <div class="row g-4">

        <!-- Alunos -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">👨‍🎓</div>
                        <h5 class="card-title mt-2">Alunos</h5>
                        <p class="text-muted small">Gerenciar estudantes</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Turmas -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">🏫</div>
                        <h5 class="card-title mt-2">Turmas</h5>
                        <p class="text-muted small">Organizar classes</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Professores -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">👩‍🏫</div>
                        <h5 class="card-title mt-2">Professores</h5>
                        <p class="text-muted small">Gestão docente</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Disciplinas -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">📚</div>
                        <h5 class="card-title mt-2">Disciplinas</h5>
                        <p class="text-muted small">Matérias cadastradas</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Notas -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">🧾</div>
                        <h5 class="card-title mt-2">Notas</h5>
                        <p class="text-muted small">Lançamento de notas</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Horários -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">⏰</div>
                        <h5 class="card-title mt-2">Horários</h5>
                        <p class="text-muted small">Grade de aulas</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Relatórios -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">📊</div>
                        <h5 class="card-title mt-2">Relatórios</h5>
                        <p class="text-muted small">Análises e dados</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Configurações -->
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 30px;">⚙️</div>
                        <h5 class="card-title mt-2">Configurações</h5>
                        <p class="text-muted small">Ajustes do sistema</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

@endsection