<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }
    public function register(){

        return view ('register');
    }
    public function processoDeRegistro(Request $request)
    {
        // Valida as entradas do formulário
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // Adicione validação para o nome
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'cpf' => 'required|unique:users',
            'date' => 'required|date', // Valide o formato da data
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->date = $request->date;
            $user->cpf = $request->cpf;
            $user->password = Hash::make($request->password);
            $user->role = 'costumer'; // Defina o papel padrão
            $user->save();

            return redirect()->route('login.index')->with('msg', 'Você foi cadastrado com sucesso');
        } else {
            return redirect()->route('login.register')
                ->withInput()  // Mantém os dados de entrada no formulário
                ->withErrors($validator);   // Passa os erros de validação para a visão
        }
    }

 

    public function store(Request $request)
{
    // Validação dos dados
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Campo de email é obrigatório',
        'email.email' => 'Esse email é inválido',
        'password.required' => 'Campo de senha é obrigatório',
    ]);

    // Tenta autenticar o usuário
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard')); // Redireciona para a página principal ou desejada
    } else {
        return redirect()->route('login.index')->with('err', 'Email ou senha inválido');
    }
}
    public function destroy (){

        Auth::logout();
        return redirect('/'); // Redireciona para a página de login após logout

    }

}

