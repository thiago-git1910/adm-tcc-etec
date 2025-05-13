<?php

namespace App\Http\Controllers;
use App\Models\Contratante;
use App\Models\Profissional;
use App\Models\Pedido;
use App\Models\Servico;

use Illuminate\Support\Facades\DB;




class AdminController extends Controller
{


    public function index()
    {



        $user = auth()->user();

        // cria a variavel de contador, recebe o model e o metodo :: count(); conta quantos cadastros tem na tbcontratantes
        $acountContratantes = Contratante::count();
        $acountContratados = Profissional::count();
        $contadorServicos = Servico::count();
        $contadorPedidos = Pedido::count();




        $contadorServicosPedidos = Pedido::join('tbservicos', 'tbSolicitarPedido.idServicos', '=', 'tbservicos.idServicos')
            ->select('tbservicos.nomeServicos', DB::raw('count(tbSolicitarPedido.idSolicitarPedido) as total'))
            ->groupBy('tbservicos.nomeServicos')
            ->get();




        $labels = $contadorServicosPedidos->pluck('nomeServicos');
        $data = $contadorServicosPedidos->pluck('total');





        // Contagem de cadastros por mês para Contratantes
        $cadastrosMesContratante = Contratante::select([
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        ])
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // Contagem de cadastros por mês para Profissionais
        $cadastrosMesProfissional = Profissional::select([
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        ])
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();


        $mes = [];
        $totalContratantes = [];
        $totalProfissionais = [];

        // Preenche os meses e totais de contratantes
        foreach ($cadastrosMesContratante as $contratante) {
            $mes[] = $contratante->mes;
            $totalContratantes[] = $contratante->total;
        }

        // Preenche os totais de profissionais
        foreach ($cadastrosMesProfissional as $profissional) {
            $totalProfissionais[] = $profissional->total;
        }
        $cadastroMes = implode(',', $mes);
        $contratanteTotal = implode(',', $totalContratantes);
        $profissionalTotal = implode(',', $totalProfissionais);



        $concluidosCount = Pedido::where('andamentoPedido', 'concluido')->count();



        $zonaProfissionais = Profissional::select('regiaoContratado', DB::raw('COUNT(*) as total'))
            ->groupBy('regiaoContratado')
            ->get();


        $labelRegiao = $zonaProfissionais->pluck('regiaoContratado')->toArray();
        $dataNumRegiao = $zonaProfissionais->pluck('total')->toArray();


        $topProfissionais = Profissional::query()
        ->orderByDesc('valorTotalRecebido') // Ordena em ordem decrescente
        ->take(3) // Limita a 3 resultados
        ->get();




        return view('/admin/DashboardAdmin', compact(
            'acountContratantes',
            'contadorServicos',
            'profissionalTotal',
            'acountContratados',
            'contratanteTotal',
            'contadorServicosPedidos',
            'contadorPedidos',
            'cadastroMes',
            'labels',
            'data',
            'concluidosCount',
            'user',
            'zonaProfissionais',
            'labelRegiao',
            'dataNumRegiao',
            'topProfissionais'
        ));
    }
}





