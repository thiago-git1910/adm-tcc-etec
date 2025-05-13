@extends('layouts.main')

@section('title', 'Editar Serviço')

@section('contentAdmin')

<link rel="stylesheet" href="{{ asset('css/editServico.css') }}">
<div class="title">
        <p class="titleDashboard">Editar serviço</p>
    </div>

<div class="tudo">

    <div class="box">


        <div class="enchecao">
        <p>Edite e adicione dados sobre o serviço disponivel abaixo!</p>
        </div>

        <div class="form-group">

            <label for="nomeServicos">Nome do serviço</label>
            <input type="text" class="form-control" name="nomeServicos" value="{{ old('nomeServicos', $servico->nomeServicos) }}" required>


            <label for="categoriaServicos">Categoria</label>
            <input type="text" class="form-control" name="categoriaServicos" value="{{ old('nomeServicos', $servico->nomeServicos) }}" required>

            <label for="nomeServicos">Descrição do serviço</label>
            <textarea type="text" class="form-control" name="descServicos" value="{{ old('descServicos', $servico->descServicos) }}" required></textarea>


            <div class="botoes">
            <button type="submit" class="btn">Atualizar Serviço</button>
            <button type="submit" class="btn-cancel">Cancelar</button>
            </div>
        </div>


    </div>




</div>

@endsection
