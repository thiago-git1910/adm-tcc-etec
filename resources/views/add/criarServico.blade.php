@extends('layouts.main')

@section('title', 'Dashboard')

@section('contentAdmin')

<link rel="stylesheet" href="{{ asset('css/criarServico.css') }}">


            <div class="title">
                <p class="titleservico">Criar serviço</p>
            </div>
           

    @if(session('msg'))
        <div class="alert alert-success">
            {{ session('msg') }}
        </div>
    @endif

    <div class="tudo">

    <form action="{{ route('inserir.servico') }}" method="POST">
        @csrf
        <div class="form-group">
    <label for="nomeServicos">Nome do Serviço:</label>
    <input type="text" class="form-control" id="nomeServicos" name="nomeServicos" required>
</div>

<div class="form-group">
    <label for="categoriaServicos">Categoria:</label>
    <input list="categorias" class="form-control" id="categoriaServicos" name="categoriaServicos" required>
    <datalist id="categorias">
        <option value="Manutenção e Reparos Domésticos">
        <option value="Construção e Reformas">
        <option value="Serviços Automotivos">
        <option value="Tecnologia da Informação">
        <option value="Design e Multimídia">
        <option value="Serviços Domésticos e Pessoais">
        <option value="Consultoria e Assessoria">
    </datalist>
</div>

        
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea class="form-control" id="descricao" name="descServicos" required></textarea>
        </div>

        <button  type="submit" class="btn" >Salvar</button >
    </form>
    </div>

@endsection
