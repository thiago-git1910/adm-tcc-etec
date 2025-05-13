@extends('layouts.main')

@section('title', 'admins')

@section('contentAdmin')


<link rel="stylesheet" href="{{ asset('css/admins.css') }}">

<div class="main p-3">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Gerenciar administradores</h2>

            <table class="table">

                <thead >
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nascimento</th>
                        <th>Cpf</th>
                        <th>Edit</th>
                        <th>Excluir</th>
                    </tr>
                </thead>


                <tbody>
                    @foreach ($users as $user)
                    <tr>
                       
                        <td>
                            <a href="{{ route('edit.admins', $user->id) }}" class="btn btn-info edit-btn">
                                <ion-icon name="create-outline"></ion-icon> Editar
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('delete.admins', $user->id) }}" method="POST">
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

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection
