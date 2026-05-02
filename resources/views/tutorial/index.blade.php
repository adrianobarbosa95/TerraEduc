@extends('layouts.public')

@section('content')

<div class="container d-flex justify-content-center align-items-center" style="min-height:80vh;">

    <div class="col-lg-8">

        <!-- HEADER -->
        <div class="text-center mb-4">
            <h2 class="fw-semibold">📘 Como usar o sistema</h2>
            <p class="text-muted">
                Siga a sequência correta para evitar erros no cadastro
            </p>
        </div>

        <div class="card border-0 shadow-sm p-4 rounded-4">

            <div id="tutorialSteps">

                <!-- 1 -->
                <div class="step">
                    <h5>1. Cadastro de Turmas</h5>
                    <p class="text-muted">
                        A primeira etapa é cadastrar as turmas.
                    </p>

                    <ul class="text-muted">
                        <li>Acesse o menu <strong>Turmas</strong></li>
                        <li>Clique em <strong>Nova Turma</strong></li>
                        <li>Informe nome, modalidade, ano e unidades (2 ou 3)</li>
                        <li>Se for semestral, informe o período (ex: M1 ou M2)</li>
                    </ul>

                    <div class="alert alert-info">
                        ⚠️ Sem turmas cadastradas, não é possível cadastrar alunos ou disciplinas.
                    </div>
                </div>

                <!-- 2 -->
                <div class="step d-none">
                    <h5>2. Cadastro de Alunos (Importação)</h5>

                    <p class="text-muted">
                        O cadastro de alunos é feito exclusivamente por importação de arquivo.
                    </p>

                    <ul class="text-muted">
                        <li>Acesse <strong>Alunos</strong></li>
                        <li>Selecione a turma</li>
                        <li>Envie o arquivo HTML da lista de presença</li>
                    </ul>

                    <div class="alert alert-warning">
                        📌 O arquivo deve ser exportado do <strong>SIGEduc</strong>.
                    </div>

                    <div class="alert alert-success">
                        ✔ Os alunos já serão vinculados automaticamente à turma selecionada.
                    </div>
                </div>

                <!-- 3 -->
                <div class="step d-none">
                    <h5>3. Cadastro de Disciplinas</h5>

                    <p class="text-muted">
                        Após cadastrar turmas e alunos, cadastre as disciplinas.
                    </p>

                    <ul class="text-muted">
                        <li>Acesse <strong>Disciplinas</strong></li>
                        <li>Informe o nome da disciplina</li>
                        <li>A disciplina pertence a um professor</li>
                    </ul>

                    <div class="alert alert-info">
                        ✔ Uma disciplina pode ser vinculada a várias turmas.
                    </div>
                </div>

                <!-- 4 -->
                <div class="step d-none">
                    <h5>4. Definir horários e turmas</h5>

                    <p class="text-muted">
                        Agora você deve organizar o funcionamento da disciplina.
                    </p>

                    <ul class="text-muted">
                        <li>Associe a disciplina às turmas</li>
                        <li>Defina os dias da semana</li>
                        <li>Defina os horários (slots)</li>
                        <li>Informe o turno (M, T ou N)</li>
                    </ul>

                    <div class="alert alert-warning">
                        ⚠️ Sem horário definido, o plano de aula não será gerado corretamente.
                    </div>
                </div>

                <!-- 5 -->
                <div class="step d-none">
                    <h5>5. Definir regras de avaliação</h5>

                    <p class="text-muted">
                        Configure quantas avaliações cada unidade terá.
                    </p>

                    <ul class="text-muted">
                        <li>Escolha a turma e disciplina</li>
                        <li>Defina a unidade (1, 2 ou 3)</li>
                        <li>Informe a quantidade de avaliações</li>
                    </ul>

                    <div class="alert alert-info">
                        ℹ️ Essa configuração não é definitiva e pode ser alterada depois.
                    </div>
                </div>

                <!-- 6 -->
                <div class="step d-none">
                    <h5>6. Cadastro de Avaliações</h5>

                    <p class="text-muted">
                        Agora você cria as avaliações reais.
                    </p>

                    <ul class="text-muted">
                        <li>Informe nome da avaliação</li>
                        <li>Defina data (opcional)</li>
                        <li>Defina o valor da avaliação</li>
                        <li>Associe à unidade correta</li>
                    </ul>

                    <div class="alert alert-success">
                        ✔ A soma das avaliações da unidade deve chegar a 10 pontos.
                    </div>
                </div>

                <!-- 7 -->
                <div class="step d-none">
                    <h5>7. Lançamento de Notas</h5>

                    <p class="text-muted">
                        Após criar avaliações, você pode lançar as notas.
                    </p>

                    <ul class="text-muted">
                        <li>Selecione a avaliação</li>
                        <li>Informe a nota de cada aluno</li>
                    </ul>

                    <div class="alert alert-info">
                        ✔ O sistema calcula automaticamente a média final.
                    </div>
                </div>

                <!-- 8 -->
                <div class="step d-none">
                    <h5>8. Acesso do Aluno</h5>

                    <p class="text-muted">
                        Os alunos podem acessar o sistema com matrícula e senha.
                    </p>

                    <ul class="text-muted">
                        <li>Visualizar notas por disciplina</li>
                        <li>Ver médias por unidade</li>
                        <li>Acompanhar plano de aula</li>
                    </ul>
                </div>

            </div>

            <!-- PROGRESSO -->
            <div class="text-center mt-3 text-muted small" id="stepIndicator"></div>

            <!-- CONTROLES -->
            <div class="mt-4 d-flex justify-content-between">
                <button class="btn btn-outline-secondary" onclick="prevStep()">⬅ Voltar</button>
                <button class="btn btn-primary" id="nextBtn" onclick="nextStep()">Próximo ➡</button>
            </div>

        </div>

    </div>

</div>

<script>

let currentStep = 0;
const steps = document.querySelectorAll('.step');
const indicator = document.getElementById('stepIndicator');
const nextBtn = document.getElementById('nextBtn');

function showStep(index) {
    steps.forEach((step, i) => {
        step.classList.toggle('d-none', i !== index);
    });

    indicator.innerText = `Passo ${index + 1} de ${steps.length}`;

    // muda botão no último passo
    if (index === steps.length - 1) {
        nextBtn.innerText = 'Finalizar ✔';
    } else {
        nextBtn.innerText = 'Próximo ➡';
    }
}

function nextStep() {

    // se for último passo → FINALIZA
    if (currentStep === steps.length - 1) {

        // marca que já viu
        localStorage.setItem('tutorial_visto', 'sim');

        // redireciona
        window.location.href = "{{ route('welcome') }}"; 
        return;
    }

    currentStep++;
    showStep(currentStep);
}

function prevStep() {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
}

showStep(currentStep);

</script>

@endsection