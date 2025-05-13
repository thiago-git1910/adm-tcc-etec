<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profissional;
use App\Models\Contratante;
use App\Models\Pedido;


class UsersController extends Controller
{

    public function index(Request $request){

        $users = User::all();

        $contratantes = Contratante::all();
        $contratados = Profissional::all();

       // Obtém o termo de busca
    $search = $request->input('search');

    // Busca em Contratantes
    $contratantes = Contratante::query()
        ->when($search, function ($query, $search) {
            $query->where('nomeContratante', 'like', "%{$search}%")
                  ->orWhere('emailContratante', 'like', "%{$search}%")
                  ->orWhere('cpfContratante', 'like', "%{$search}%");
        })
        ->get();

    // Busca em Profissionais
    $contratados = Profissional::query()
        ->when($search, function ($query, $search) {
            $query->where('nomeContratado', 'like', "%{$search}%")
                  ->orWhere('emailContratado', 'like', "%{$search}%")
                  ->orWhere('cpfContratado', 'like', "%{$search}%");
        })
        ->get();

    // Busca em Administradores (Usuários gerais)
    $users = User::query()
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
        })
        ->get();



        if (!auth()->check()) {
            return redirect()->route('login'); // Redireciona para a página de login
        }

        $user = auth()->user();
        return view('users.index', compact('user' ,'users', 'contratantes', 'contratados'));
    }

    public function toggleSuspension($id)
    {
        $profissional = Profissional::findOrFail($id);

        // Alterna o estado de suspensão
        $profissional->is_suspended = !$profissional->is_suspended;
        $profissional->save();

        // Mensagem de feedback
        $status = $profissional->is_suspended ? 'suspenso' : 'ativado';
        return redirect()->route('users.index')->with('success', "Profissional {$status} com sucesso!");
    }


    public function userAdm(){


        $user = auth()->user();

        return view('users.admins'  ,compact('user', ));

    }
    public function edit($id){

        $user = User::findOrFail($id);
// o users. é para entrar na pasta, ja o editAdmin é o nome da view
        return view('users.editAdmin', compact('user'));
    }
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cpf' => 'required|string|max:14',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->cpf = $request->cpf;
            $user->save();

            return redirect()->route('users.admins')->with('msg', 'Serviço atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao atualizar o usuário: ' . $e->getMessage());
        }
    }

    public function destroy($idContratante){
        try {
            $contratante = Contratante::findOrFail($idContratante);
            $contratante->delete();
            return redirect()->back()->with('msg', '');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors('Erro ao excluir o contratante: ' . $e->getMessage());
        }
    }



    public function destroyContratado($idContratado){
        try {
            $contratado = Profissional::findOrFail($idContratado);
            $contratado->delete();
            return redirect()->back()->with('msg', '');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors('Erro ao excluir o contratado: ' . $e->getMessage());
        }
    }

    public function destroyAdmin($idAdmin){
        try {
            $adm = User::findOrFail($idAdmin);
            $adm->delete();
            return redirect()->back()->with('msg', '');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors('Erro ao excluir o contratado: ' . $e->getMessage());
        }
    }


    public function clientes(){
    // users é o caminho da pasta, e os clientes é o nome da pagina
        return view('users.clientes');
    }





}
