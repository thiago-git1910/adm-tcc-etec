<?php

use App\Http\Controllers\PedidoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfissionalApiController;
use App\Http\Controllers\ContratanteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\AvaliacaoController;


// -------------------------------------- Rotas de Profissional --------------------------------------
Route::get('/pro', [ProfissionalApiController::class, 'indexApiPro']);
Route::get('/pro/{id}', [ProfissionalApiController::class, 'showApi']);
Route::post('/proo', [ProfissionalApiController::class, 'storeApiPro']);
Route::post('/authpro', [ProfissionalApiController::class, 'authPro']);
Route::post('/pusher/authpro', [ProfissionalApiController::class, 'authorizePusher']);
Route::post('/proUp/{idContratado}', [ProfissionalApiController::class, 'update']);

// Buscar dados do Profissional
Route::middleware('auth:sanctum')->get('/perfilPro', [ProfissionalApiController::class, 'dadosProfissionais']);
Route::middleware('auth:sanctum')->get('/profissional/{idContratado}/dadosProfissionais', [ProfissionalApiController::class, 'dadosProfissionais']);

// -------------------------------------- Rotas de Contratante --------------------------------------
Route::get('/cli', [ContratanteController::class, 'indexApi']);
Route::get('/cli/{id}', [ContratanteController::class, 'showApi']);
Route::post('/clii', [ContratanteController::class, 'storeApi']);
Route::put('/cli/{idContratante}', [ContratanteController::class, 'update']);
Route::post('/auth', [ContratanteController::class, 'auth']);
Route::post('/pusher/auth', [ContratanteController::class, 'authorizePusher']);

// -------------------------------------- Rotas de Pedidos ------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pedido', [PedidoController::class, 'store']);  // Criar pedido
    Route::get('/pedidos', [PedidoController::class, 'indexPedido']);  // Listar pedidos
    Route::get('/meusPedidos/{idContratante}', [PedidoController::class, 'meusPedidos']);  // Pedidos do contratante
    Route::get('/profissional/{idContratado}/pedidos', [PedidoController::class, 'pedidosPendentes']);  // Pedidos do profissional
    Route::put('/pedido/aceitar/{idSolicitarPedido}', [PedidoController::class, 'aceitarPedido']);
    Route::get('/pedido/{idSolicitarPedido}', [PedidoController::class, 'pedido']);
    Route::get('/pedidos/aceitos/{idContratado}', [PedidoController::class, 'meusPedidosAceitos']);
    Route::post('/pedidos/{idSolicitarPedido}/contrato', [PedidoController::class, 'storeContrato']);
    Route::patch('/pedidos/{idSolicitarPedido}/pendente', [PedidoController::class, 'pendentePedido']);
    Route::patch('/pedidos/{idSolicitarPedido}/acoes', [PedidoController::class, 'acoesPedido']);
    Route::get( '/contratos/recebidos/{idContratante}', [PedidoController::class , 'meusContratos']);
    Route::patch('/contratos/{idSolicitarPedido}/acao', [PedidoController::class, 'acaoContrato']);
    Route::get( '/pedidos/finalizados/{idContratante}', [PedidoController::class , 'meusPedidosFinalizadosCliente']);

    
    Route::get( '/pedidos/finalizados', [PedidoController::class , 'meusPedidosFinalizadosProfissional']);


});
// -------------------------------------- Rotas de Serviços ------------------------------------------
Route::get('/servicos', [ServicoController::class, 'servicoIndex']);

// -------------------------------------- Rotas de Chat ---------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chat-room/{contactId}', [ChatController::class, 'createOrGetChatRoom']);  // Criar ou obter sala de chat
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);  // Enviar mensagem
    Route::get('/chat/messages/{roomId}', [ChatController::class, 'getMessages']);  // Obter mensagens da sala de chat

});

// -------------------------------------- Rotas de Avaliacao ---------------------------------------------


    Route::post('/avaliacao', [AvaliacaoController::class, 'store']);
    Route::get('/avaliacoes', [AvaliacaoController::class, 'index']);
    Route::get('/avaliacoes/{idContratado}', [AvaliacaoController::class, 'getAvaliacoesByContratado']);

// -------------------------------------- ROTA DE DENUNCIAS -----------------------

Route::middleware('auth:sanctum')->group(function () {

Route::post('/denuncia', [AtendimentoController::class, 'store']);

});
