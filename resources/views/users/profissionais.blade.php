@extends('layouts.main')

@section('title', 'Dashboard')

@section('contentAdmin')

<div class="main p-3">
    <link rel="stylesheet" href="{{ asset('css/clientes.css') }}">


    <div class="titleContainer">
        <p class="titleClientes" id="titleDashboard">Solicitações em aberto</p>
    </div>

    <div class="containerEstadoCli">
        <div class="estado">
            <!-- w de white  -->
            <div class="emAbertoCli" id="w">
                <p>Em aberto</p>
            </div>
            <!-- g de green -->
            <div class="aceitos" id="g">
                <p>Aceitos</p>
            </div>
            <!-- r de red -->
            <div class="negados" id="r">
                <p>Negados</p>
            </div>
        </div>
    </div>
    
    <div id="containerClientes">
        <div class="tabelaClientes">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <!-- O conteúdo dos cabeçalhos pode ser adicionado aqui -->
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="imgCliente"></div>
                                </td>
                                <td></td>
                                <td></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/clientes.js') }}"></script>
@endsection
