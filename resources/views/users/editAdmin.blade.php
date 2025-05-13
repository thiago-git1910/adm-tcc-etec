@extends('layouts.main')

@section('title', 'editAdmin')

@section('contentAdmin')

<link rel="stylesheet" href="{{ asset('css/editAdmin.css') }}">

<!-- título -->
<div class="main p-3">
    <div class="row">
        <div class="col-md-12">
            <div class="title">
                <p>Perfil Administrador</p>
            </div>

            <!-- parte do usuario 1: foto -->
            <div class="tudo">
                <div class="parteum">
                    <div class="foto"></div>
                    <div class="ptbotao">
                        <button>Alterar foto</button>
                    </div>
                </div>

                <!-- parte do usuario 2: Infos -->
                <div class="partedois">
                    <div class="formulário">
                        <form action="{{ route('update.admins', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group" id="inputs">
                                <label for="nomeServicos"><p class="nomezinho">Nome do administrador:</p></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                            </div>

                            <div class="form-group" id="inputs">
                                <label for="cpf"><p class="nomezinho">CPF:</p></label>
                                <input type="text" class="form-control" id="cpf" name="cpf" value="{{ $user->cpf }}" required>
                            </div>

                            <div class="form-group" id="inputs">
                                <label for="data"><p class="nomezinho">Data de Nascimento:</p></label>
                                <input type="date" class="form-control" id="data" name="data" value="{{ $user->dataDeNascimento }}" required>
                            </div>

                            <div class="form-group" id="inputs">
                                <label for="email"><p class="nomezinho">E-mail:</p></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                            </div>

                            <div class="botoes">
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Excluir Usuário
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal para Exclusão -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Excluir Usuário</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Tem certeza que deseja excluir esse usuário? As alterações não podem ser desfeitas.
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('delete.admins', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                </form>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
