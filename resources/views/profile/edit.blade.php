@extends('layouts.main')

@section('content')

<style>
.link-btn {
    border-radius: 10px;
    transition: all 0.25s ease;
    font-weight: 500;
    color: #163a63;
}

.link-btn:hover {
    background: #0e2747;
    color: #fff !important;
    transform: translateX(5px);
}
</style>

<div style="background:#f5f7fa; min-height:100vh; padding:50px 0;">
    <div class="container">

        <!-- HEADER -->
        <div class="mb-4 p-4 text-white rounded-4"
             style="background: linear-gradient(135deg,#0e2747,#163a63);">

            <div class="d-flex align-items-center gap-4">

                <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://via.placeholder.com/120' }}"
                     class="rounded-circle"
                     style="width:100px;height:100px;object-fit:cover;">

                <div>
                    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                    <small>{{ auth()->user()->email }}</small><br>

                    <a href="/professores/{{ auth()->user()->slug }}"
                       target="_blank"
                       class="btn btn-light btn-sm mt-2">
                        Ver perfil público
                    </a>
                </div>

            </div>
        </div>

        <div class="row">

            <!-- ESQUERDA -->
            <div class="col-md-8">

                <!-- PERFIL -->
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body">

                        <h5 class="mb-3">Informações do Perfil</h5>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', auth()->user()->name) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', auth()->user()->email) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto</label>
                                <input type="file" name="photo" class="form-control">
                            </div>

                            <h5 class="mt-4">Sobre você</h5>

                            <div class="mb-3">
                                <textarea name="bio" class="form-control" rows="5"
                                    placeholder="Fale sobre você...">{{ old('bio', auth()->user()->bio) }}</textarea>
                                    
                            </div>

                            <hr>

                            <h5 class="mt-4">Links</h5>

                            <div class="mb-3">
                                <label>Lattes</label>
                                <input type="text" name="lattes" class="form-control"
       value="{{ old('lattes', auth()->user()->lattes) }}">

@error('lattes')
    <small class="text-danger">{{ $message }}</small>
@enderror
                                       
                            </div>

                            <div class="mb-3">
                                <label>GitHub</label>
                                <input type="text" name="github" class="form-control"
       value="{{ old('github', auth()->user()->github) }}">

@error('github')
    <small class="text-danger">{{ $message }}</small>
@enderror
                            </div>

                            <div class="mb-3">
                                <label>LinkedIn</label>
                               <input type="text" name="linkedin" class="form-control"
       value="{{ old('linkedin', auth()->user()->linkedin) }}">

@error('linkedin')
    <small class="text-danger">{{ $message }}</small>
@enderror
                            </div>

                            <div class="mb-3">
                                <label>Instagram</label>
                               <input type="text" name="instagram" class="form-control"
       value="{{ old('instagram', auth()->user()->instagram) }}">

@error('instagram')
    <small class="text-danger">{{ $message }}</small>
@enderror
                            </div>

                            <div class="mb-3">
                                <label>Site</label>
                               <input type="text" name="website" class="form-control"
       value="{{ old('website', auth()->user()->website) }}">

@error('website')
    <small class="text-danger">{{ $message }}</small>
@enderror
                            </div>

                            <button class="btn btn-primary">
                                Salvar alterações
                            </button>

                        </form>

                    </div>
                </div>

                <!-- SENHA -->
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">

                        <h5 class="mb-3">Alterar Senha</h5>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                          <input type="password" name="current_password" class="form-control mb-2" placeholder="Senha atual">
 

<input type="password" name="password" class="form-control mb-2" placeholder="Nova senha">


<input type="password" name="password_confirmation" class="form-control mb-3" placeholder="Confirmar senha">
@error('password', 'updatePassword')
    <small class="text-danger d-block mt-1">{{ $message }}</small>
@enderror
@if (session('status') === 'password-updated')
    <div class="alert alert-success d-flex align-items-center">
        ✅ Senha atualizada com sucesso!
    </div>
@endif
                            <button class="btn btn-dark">
                                Atualizar senha
                            </button>

                        </form>

                    </div>
                </div>

            </div>

            <!-- DIREITA -->
            <div class="col-md-4">

                <!-- RESUMO -->
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body">
                        <h6>Resumo</h6>
                        <p class="text-muted small">
                            {{ auth()->user()->bio ?? 'Adicione uma bio.' }}
                        </p>
                    </div>
                </div>

                <!-- LINKS BONITOS -->
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body">

                        <h6 class="mb-3 fw-semibold" style="color:#0e2747;">Links</h6>

                        <div class="d-grid gap-2">

                            @if(auth()->user()->lattes)
                                <a href="{{ auth()->user()->lattes }}" target="_blank" class="btn btn-light border link-btn">
                                    🎓 Lattes
                                </a>
                            @endif

                            @if(auth()->user()->github)
                                <a href="{{ auth()->user()->github }}" target="_blank" class="btn btn-light border link-btn">
                                    💻 GitHub
                                </a>
                            @endif

                            @if(auth()->user()->linkedin)
                                <a href="{{ auth()->user()->linkedin }}" target="_blank" class="btn btn-light border link-btn">
                                    🔗 LinkedIn
                                </a>
                            @endif

                            @if(auth()->user()->instagram)
                                <a href="{{ auth()->user()->instagram }}" target="_blank" class="btn btn-light border link-btn">
                                    📸 Instagram
                                </a>
                            @endif

                            @if(auth()->user()->website)
                                <a href="{{ auth()->user()->website }}" target="_blank" class="btn btn-light border link-btn">
                                    🌐 Site
                                </a>
                            @endif

                        </div>

                    </div>
                </div>

                <!-- ZONA DE RISCO -->
                <!-- ZONA DE RISCO -->
<div class="card border-danger border-2 rounded-4">
    <div class="card-body">

        <h6 class="text-danger">Zona de risco</h6>

        <p class="text-muted small">
            Uma vez excluída, sua conta e todos os dados serão permanentemente removidos.
            <strong>Esta ação não pode ser desfeita.</strong>
        </p>

        <!-- ALERTA CLARO -->
        <div class="alert alert-warning py-2 small">
            ⚠️ Para excluir sua conta, digite sua senha atual para confirmar.
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Digite sua senha"
                   required>

            <!-- ERRO CORRETO (IMPORTANTÍSSIMO) -->
            @error('password', 'userDeletion')
                <div class="alert alert-danger mt-2 py-2 small">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit"
                    class="btn btn-danger w-100 mt-3"
                    onclick="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')">
                Excluir conta
            </button>

        </form>

    </div>
</div>

            </div>

        </div>

    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title text-danger">Excluir conta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <p class="text-muted small">
                        Tem certeza? Todos os dados serão apagados permanentemente.
                    </p>

                    <input type="password" name="password" class="form-control" placeholder="Digite sua senha" required>

                    @error('password', 'userDeletion')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger">Excluir conta</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection