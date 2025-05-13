<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servico;
use Illuminate\Support\Facades\Auth; // Import correto

class ServicoController extends Controller
{
    public function servico()
    {
        $user = Auth::user(); // Uso correto da facade Auth

        if (!$user) {
            return redirect()->route('/')->with('error', 'Você precisa estar logado para acessar essa página.');
        }

        $servicos = Servico::all();
        return view('add.servico', compact('servicos', 'user'));
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $servicos = Servico::query()
            ->when($search, function ($query, $search) {
                $query->where('nomeServicos', 'like', "%{$search}%")
                    ->orWhere('categoriaServicos', 'like', "%{$search}%")
                    ->orWhere('descServicos', 'like', "%{$search}%");
            })
            ->get();

        $user = Auth::user(); // Uso correto da facade Auth
        return view('add.servico', compact('servicos', 'user'));
    }

    public function create()
    {
        $user = Auth::user(); // Uso correto da facade Auth
        return view('add.criarServico', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomeServicos' => 'required|string|max:255',
            'descServicos' => 'required|string',
            'categoriaServicos' => 'required|string',
        ]);

        $servico = new Servico;
        $servico->nomeServicos = $request->nomeServicos;
        $servico->categoriaServicos = $request->categoriaServicos;
        $servico->descServicos = $request->descServicos;

        $servico->save();

        return redirect()->route('add.servico')->with('msg', 'Serviço criado com sucesso!');
    }

    public function edit($idServicos)
    {
        $user = Auth::user(); // Uso correto da facade Auth

        // Certifique-se de usar findOrFail para lidar com IDs inexistentes
        $servico = Servico::findOrFail($idServicos);

        // Retorne a view de edição com o serviço específico
        return view('add.editServico', compact('servico', 'user'));
    }

    public function update(Request $request, $idServicos)
    {
        $request->validate([
            'nomeServicos' => 'required|string|max:255',
            'categoriaServicos' => 'required|string',
            'descServicos' => 'required|string',
            'precoServicos' => 'required|string',
        ]);

        $servico = Servico::findOrFail($idServicos);
        $servico->nomeServicos = $request->nomeServicos;
        $servico->categoriaServicos = $request->categoriaServicos;
        $servico->descServicos = $request->descServicos;
        $servico->precoServicos = $request->precoServicos;
        $servico->save();

        return redirect()->route('add.servico')->with('msg', 'Serviço atualizado com sucesso!');
    }

    public function destroy($idServicos)
    {
        Servico::findOrFail($idServicos)->delete();

        return redirect()->route('add.servico')->with('msg', 'Serviço excluído com sucesso!');
    }

    public function servicoIndex()
    {
        $servicos = Servico::all();
        return $servicos;
    }
}
