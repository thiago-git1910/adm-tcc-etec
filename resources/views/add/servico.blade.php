@extends('layouts.main')

@section('title', 'Criando Servico')

@section('contentAdmin')
<div class="main p-3">
    <div class="row"    style="margin-left:8vh";>
        <div class="col-md-12">


    <link rel="stylesheet" href="{{ asset('css/servico.css') }}">
        
            <div class="inicio">
            <div class="header mb-4 ">
                <p>Olá,<span style="color: #ff6347; font-size:30px ">{{$user->name}}</span></p>
            </div>
            <div class="search-container">
                <form action="{{ route('servicos.index') }}" method="GET" style="display: flex;">
                    <input type="text" name="search" class="search-input" placeholder="Pesquisar..." value="{{ request('search') }}">
                    <button type="submit" class="search-btn"><i class="bi bi-search"></i></button>
                </form>
            </div>
            
            </div>
           
            
            <div class="title">
                <p class="titleservico">Serviços</p>
            </div>
           

            <div class="tudo">
            <table class="table" style="background-color: rgb(222, 222, 222)"  >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th>Editar</th>
                        <th>Excluir</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($servicos as $servico)
                    <tr>
                        <td scope="row">{{ $servico->idServicos }}</td>
                        <td>{{ $servico->nomeServicos }}</td>
                        <td>{{ $servico->categoriaServicos }}</td>
                        <td>{{ $servico->descServicos }}</td>
                        <td>
                             <!-- Botões de editar -->
                    <a href="{{ route('edit.servico', $servico->idServicos) }}" class="btn btn-info edit-btn">
                        <ion-icon name="create-outline"></ion-icon> Editar
                    </a>
                    </td>
                    <td>
                    <form action="{{ route('delete.servico', $servico->idServicos) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger delete-btn">
                            <ion-icon name="trash-outline"></ion-icon> Deletar
                        </button>
                    </form>
                    </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div class="botao">
            <a href="/criarServico" class="btn btn-primary mb-3">Adicionar Novo Serviço</a>
            </div>
        </div>

</div>
@endsection
