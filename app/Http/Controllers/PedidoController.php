<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profissional;
use App\Models\Contratante;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    // Listar todos os pedidos
    public function index()
    {
        $pedidos = Pedido::all();
        return response()->json($pedidos);
    }

    // Método para criar um novo pedido
    public function store(Request $request)
    {


        // Validação da requisição
        $validatedData = $request->validate([
            'descricaoPedido' => 'required|string|max:999',
            'idServicos' => 'required|integer',
            'idContratado' => 'required|uuid',  // UUID do profissional
            'tituloPedido' => 'required|string|max:50'
        ]);

        try {


            // Obter o UUID do contratante logado
            $validatedData['idContratante'] = Auth::user()->idContratante;

            // Criar o pedido no banco
            $pedido = Pedido::create($validatedData);

            // Verificar se o UUID do profissional é válido
            $profissional = Profissional::find($validatedData['idContratado']);

            return response()->json([
                'message' => 'Pedido criado com sucesso!',
                'pedido' => $pedido,
                'profissional' => $profissional->nomeContratado ?: 'Nenhum profissional encontrado.'
            ], 201);
        } catch (\Exception $e) {
            // Tratar erros
            $erro = $e;
            Log::info("Mensagem criada: " . $erro);
            return response()->json(['error' => 'Erro ao criar o pedido: ' . $e->getMessage()], 500);
        }
    }

    // Método para listar pedidos pendentes
    public function pedidosPendentes(Request $request)
    {
        $profissional['idContratado'] = Auth::user()->idContratado;

        if (!$profissional) {
            return response()->json(['error' => 'Nenhum profissional autenticado'], 400);
        }

        // Busca os pedidos pendentes e inclui os dados do contratante através do relacionamento
        $pedidos = Pedido::with([
            'contratante' => function ($query) {
                $query->select('idContratante', 'nomeContratante', 'emailContratante', 'telefoneContratante', 'cidadeContratante', 'bairroContratante'); // Campos do contratante que você quer trazer
            }
        ])
            ->select('idSolicitarPedido', 'descricaoPedido', 'idContratante', 'tituloPedido', 'statusPedido')
            ->where('idContratado', $profissional)
            ->where('statusPedido', 'pendente')
            ->get();



            $pedidosPendentes = $pedidos->count();



    return response()->json([
        'pedidos' => $pedidos,
        'contadorPedidos' => $pedidosPendentes,
    ]);
    }




    // Método para responder a um pedido
    public function meusPedidos()
    {
        $idContratante['idContratante'] = Auth::user()->idContratante;




        $contratante = Contratante::with([
            'pedidos' => function ($query) {
                $query->select('idSolicitarPedido', 'tituloPedido', 'idContratado', 'idContratante')
                    ->with(['contratado:idContratado,nomeContratado'])
                    ->whereIn('andamentoPedido', ['a_caminho', 'ReceberPagamento', 'pendente'])
                    ->whereIn('statusPedido', ['aceito','pendente']);
            }
        ])
            ->where('idContratante', $idContratante)

            ->first();

        if (!$contratante) {
            return response()->json(['message' => 'Contratante não encontrado'], 404);
        }

        return response()->json($contratante);
    }


    public function pedido($idSolicitarPedido)
    {
        $pedido = Pedido::find($idSolicitarPedido);

        if ($pedido) {
            return response()->json($pedido, 200);
        } else {
            return response()->json(['message' => 'Deu ruim']);
        }
    }
    public function meusPedidosAceitos()
    {
        // Obtém o ID do contratado autenticado
        $idContratado = Auth::user()->idContratado;

        // Busca pedidos aceitos e em andamento ou concluídos para o profissional autenticado
        $pedidos = Pedido::with([
            'contratante' => function ($query) {
                $query->select('idContratante', 'nomeContratante', 'emailContratante', 'telefoneContratante', 'cidadeContratante', 'bairroContratante', 'cepContratante');
            },
            'contrato' => function ($query) {
                $query->select('id', 'idSolicitarPedido', 'valor', 'data', 'hora', 'desc_servicoRealizado', 'forma_pagamento', 'status')
                ->where('status', 'aceito');
            }
        ])
            ->where( 'statusPedido', 'aceito')
            ->where( 'andamentoPedido',['a_caminho', 'ReceberPagamento', 'pendente'])
            ->where('idContratado', $idContratado)  // Usa a variável simples
            ->get();

        if ($pedidos->isEmpty()) {
            return response()->json(['message' => 'Nenhum pedido foi realizado a você']);
        }

        return response()->json($pedidos);
    }




    public function storeContrato(Request $request, $idSolicitarPedido)
    {
        try {
            $request->validate([
                'valor' => 'required|string',
                'data' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'forma_pagamento' => 'required|string',

            ]);

            // Buscar o pedido e verificar se existe
            $pedido = Pedido::findOrFail($idSolicitarPedido);

            // Atualizar o status do pedido para 'aceito'
            $pedido->statusPedido = 'aceito';
            $pedido->andamentoPedido = 'a_caminho';
            $pedido->save();

            // Criar o contrato relacionado ao pedido
            $contrato = $pedido->contrato()->create([
                'idContratante' => $pedido->idContratante,
                'idContratado' => $pedido->idContratado,
                'valor' => $request->valor,
                'data' => $request->data,
                'hora' => $request->hora,
                'desc_servicoRealizado' => $request->desc_servicoRealizado,
                'tipo_servico' => $pedido->tituloPedido,
                'status' => 'pendente',
                'forma_pagamento' => $request->forma_pagamento
            ]);

            return response()->json([
                'message' => 'Contrato criado com sucesso e pedido aceito!',
                'contrato' => $contrato,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar contrato: ' . $e->getMessage()], 500);
        }
    }


    public function meusContratos (){

        $idContratante = Auth::user()->idContratante;

        $pedido = Pedido::with([

            'contrato' => function ($query) {
                $query->select('id', 'idSolicitarPedido', 'status', 'desc_servicoRealizado', 'hora', 'valor', 'data', 'forma_pagamento')
                ->where('status', 'pendente');
            },
            'contratado' => function ($query) {
                $query->select('idContratado','nomeContratado');
            },
        ])
             ->where('idContratante', $idContratante)
             ->where('statusPedido', 'aceito')
             ->get();

             return response()->json($pedido);

        }

        public function acaoContrato($idSolicitarPedido, Request $request)
        {
            $idContratante = Auth::user()->idContratante;

            // Busca o contrato associado ao pedido e ao contratante autenticado
            $contrato = Contrato::where('idSolicitarPedido', $idSolicitarPedido)
                ->where('idContratante', $idContratante)
                ->with('contratante', 'contratado')
                ->firstOrFail();


            $acao = $request->input('acao');

            if ($acao === 'aceitar') {
                $contrato->status = 'aceito';
                $contrato->save();
                \Log::info("Contrato {$contrato->id} aceito com sucesso pelo usuário {$idContratante}");

                $pedido = Pedido::findOrFail($idSolicitarPedido);
                $pedido->data_inicio = now();
                $pedido->save();

                return response()->json(['message' => 'Contrato foi aceito e atualizado com sucesso']);
            } elseif ($acao === 'recusar') {
                $contrato->status = 'cancelado';
                $contrato->save();

                // Exclui o contrato após marcar como cancelado
                $contrato->delete();

                return response()->json(['message' => 'Contrato foi cancelado e excluído com sucesso']);
            } else {
                return response()->json(['error' => 'Ação inválida'], 400);
            }
        }

    public function pendentePedido($idSolicitarPedido)
    {
        $pedido = Pedido::with([

            'contrato' => function ($query) {
                $query->select('id', 'idSolicitarPedido', 'status', 'desc_servicoRealizado', 'hora', 'valor', 'data', 'forma_pagamento');
            },
            'contratante' => function ($query) {
                $query->select('idContratante', 'nomeContratante', 'cidadeContratante', 'bairroContratante','emailContratante','cepContratante');
            }
        ])
            ->findOrFail($idSolicitarPedido);

        if ($pedido->statusPedido !== 'aceito') {
            return response()->json(['message' => 'Pedido não pode ser iniciado, pois não foi aceito'], 403);
        }




        return response()->json([
            'message' => 'Pedido está em andamento e contrato aceito!',
            'pedido' => $pedido
        ]);
    }


    public function acoesPedido($idSolicitarPedido, Request $request)
    {
        $novaEtapa = $request->input('novaEtapa');
        $valorTotal = $request->input('valorTotal');

        Log::info("Nova etapa recebida: {$novaEtapa}");
        Log::info("Valor total recebido: {$valorTotal}");

        $pedido = Pedido::with('contrato', 'contratado')->findOrFail($idSolicitarPedido);
        Log::info("Etapa atual do pedido (andamentoPedido): " . $pedido->andamentoPedido);


        if (!$pedido->contrato || $pedido->contrato->status !== 'aceito') {
            return response()->json(['message' => 'O contrato ainda não foi aceito pelo cliente. O pedido não pode ser iniciado.'], 400);
        }
        try {
            switch ($novaEtapa) {
                case 'a_caminho':
                    if ($pedido->andamentoPedido !== 'pendente') {
                        return response()->json(['message' => 'O pedido só pode ser marcado como "a caminho" se estiver "pendente".'], 400);
                    }
                    Log::info("Pedido movido para a etapa 'a_caminho'.");
                    break;
                case 'em_andamento':
                    if ($pedido->andamentoPedido !== 'a_caminho') {
                        return response()->json(['message' => 'O pedido só pode ser marcado como "em andamento" se estiver "a caminho".'], 400);
                    }
                    Log::info("Pedido movido para a etapa 'em_andamento'.");
                    break;
                case 'ReceberPagamento':
                    if ($pedido->andamentoPedido !== 'em_andamento') {
                        return response()->json(['message' => 'O pedido só pode receber pagamento se estiver "em andamento".'], 400);
                    }
                    Log::info("Pedido movido para a etapa 'ReceberPagamento'.");
                    break;
                case 'concluido':
                    if ($pedido->andamentoPedido !== 'ReceberPagamento') {
                        return response()->json(['message' => 'O pedido só pode ser finalizado se o pagamento tiver sido recebido.'], 400);
                    }
                    $pedido->data_conclusao = now();
                    Log::info("Pedido movido para a etapa 'concluido'.");
                    break;
                default:
                    return response()->json(['message' => 'Etapa inválida.'], 400);
            }

            $pedido->andamentoPedido = $novaEtapa;
            $pedido->save();

            if ($pedido->contrato && $pedido->contratado) {
                $pedido->contrato->valor = $valorTotal;
                $pedido->contrato->save();

                $pedido->contratado->valorTotalRecebido = is_numeric($pedido->contratado->valorTotalRecebido)
                    ? $pedido->contratado->valorTotalRecebido + $valorTotal
                    : $valorTotal;
                $pedido->contratado->save();
            }

            return response()->json(['message' => 'Etapa atualizada com sucesso!', 'pedido' => $pedido]);
        } catch (ValidationException $e) {
            Log::error("Erro de validação: " . $e);
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Erro inesperado: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar o pedido: ' . $e->getMessage()], 500);
        }
    }


    public function meusPedidosFinalizadosCliente ( $idContratante){
        $idContratante = Auth::user()->idContratante;


        $pedido = Pedido::with([

            'contrato' => function ($query) {
                $query->select('id', 'idSolicitarPedido', 'status', 'desc_servicoRealizado', 'hora', 'valor', 'data', 'forma_pagamento')
                ->where('status', 'aceito');
            },
            'contratado' => function ($query) {
                $query->select('idContratado','nomeContratado');
            },


        ])
             ->where('idContratante', $idContratante)
             ->where('statusPedido', 'aceito')
             ->where('andamentoPedido','concluido')
             ->get();

             return response()->json($pedido);

    }
   public function meusPedidosFinalizadosProfissional()
    {
        // Obtém o profissional autenticado
        $profissional = Auth::user();

        // Carrega os pedidos relacionados ao profissional que estão concluídos
        $pedidosFinalizados = $profissional->pedidos()
        ->select('idSolicitarPedido', 'tituloPedido', 'descricaoPedido', 'data_conclusao', 'andamentoPedido')
        ->with([
            'contratante:idContratante,nomeContratante,emailContratante',
            'contrato:id,idSolicitarPedido,status,valor,data,hora,forma_pagamento',
        ])
        ->where('andamentoPedido', 'concluido')
        ->get();


        // Retorna os pedidos finalizados no formato JSON
        return response()->json($pedidosFinalizados, 200);
    }



}
