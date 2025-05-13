@extends('layouts.main')

@section('title', 'clientes')

@section('contentAdmin')

<div class="main p-3">
    <link rel="stylesheet" href="{{ asset('css/clientes.css') }}">

    <div class="titleContainer">
        <p class="titleClientes" id="titleDashboard">Atendimento | Perguntas</p>
    </div>

    <div class="containerEstadoCli">
        <div class="estado">
            <div class="status emAbertoCli">Em aberto</div>
            <div class="status emAndamentoCli">Em andamento</div>
            <div class="status concluidasCli">Concluídas</div>
        </div>
    </div>

    <div id="containerPergunta">

        <div class="pergunta">
            <div class="imgCliente"></div>
            <div class="conteudoPergunta">
                <p class="nomeCliente">Aline Mendonça</p>
                <p class="textoPergunta">Como cancelar um serviço após já ter fechado?</p>
            </div>
            <p class="dataPergunta">25/10/2024</p>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script src="{{ asset('js/clientes.js') }}"></script>
