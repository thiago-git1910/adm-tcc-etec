<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\DenunciaTratadaMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Profissional;
use App\Models\Denuncia;
use Illuminate\Support\Facades\Auth;
class AtendimentoController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // Filtra denúncias por status
    $denunciasEmAberto = Denuncia::with(['contratante', 'contratado'])
        ->where('status', 'emAberto')
        ->get();

    $denunciasEmAndamento = Denuncia::with(['contratante', 'contratado'])
        ->where('status', 'emAnalise')
        ->get();

    $denunciasConcluidas = Denuncia::with(['contratante', 'contratado'])
        ->where('status', 'concluido')
        ->get();

        Log::info("Tentando carregar view: atendimentos.atendimentos");

    // Retorna as denúncias filtradas para a view
    return view('atendimentos/atendimentos', compact(
        'user',
        'denunciasEmAberto',
        'denunciasEmAndamento',
        'denunciasConcluidas'
    ));
}


    public function store(Request $request)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuário não autenticado.'], 403);
        }

        // Valida os campos do request
        $validateData = $request->validate([
            'descricao' => 'required|string|max:300',
            'idContratante' => 'required|uuid',
            'idContratado' => 'required|uuid',
            'categoria' => 'required|string',
            'status' => 'required|string|in:emAberto', // Validação corrigida
            'imagemDenuncia'=>"nullable|string",
        ]);

        try {
            // Obtém o ID do contratante autenticado
            $request['idContratante'] = Auth::user()->idContratante;


            $denuncia = Denuncia::create($validateData);

            // Obtém o profissional denunciado
            $profissional = Profissional::findOrFail($request->idContratado);

            return response()->json([
                'message' => 'Denúncia realizada com sucesso.',
                'denuncia' => $denuncia,
                'profissional' => $profissional->nomeContratado,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar denúncia: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar denúncia.'], 500);
        }
    }

    public function acaoAnalise($idDenuncia, Request $request)
    {
        // Validação da ação recebida
        $validatedData = $request->validate([
            'acao' => 'required|string|in:emAnalise,concluido',
            'motivo' => 'required|string',
        ]);

        $acao = $validatedData['acao'];
        $assunto = "Denúncia";

        // Obtenção da denúncia
        $denuncia = Denuncia::with(['contratante', 'contratado'])
            ->where('id', $idDenuncia)
            ->firstOrFail();

        // Atualização do status da denúncia
        $denuncia->status = $acao;
        $denuncia->save();

        // Suspender o profissional ao concluir a denúncia
    if ($acao === 'concluido') {
        $profissional = $denuncia->contratado;
        if ($profissional) {
            $profissional->is_suspend = true; // Suspender a conta
            $profissional->save();
            Log::info("Profissional {$profissional->id} suspenso devido à denúncia concluída.");
        }
    }

        // E-mails para contratante e contratado
        $emailContratante = $denuncia->contratante->emailContratante ?? null;
        $emailContratado = $denuncia->contratado->emailProfissional ?? null;

        // Enviar e-mail para o contratante
        if ($emailContratante) {
            $msgContratante = $acao === 'emAnalise'
                ? 'O pedido de denúncia está em análise. Por favor, aguarde 2 dias úteis para uma resposta.'
                : 'A denúncia foi concluída. Obrigado por utilizar nossos serviços.';

            try {
                Mail::to($emailContratante)->send(new DenunciaTratadaMail($msgContratante, $assunto));
            } catch (\Exception $e) {
                Log::error("Erro ao enviar e-mail para o contratante: {$e->getMessage()}");
            }
        }

        // Enviar e-mail para o contratado
        if ($emailContratado) {
            $msgContratado = $acao === 'concluido'
                ? 'Você foi denunciado, e a denúncia foi concluída. Sua conta foi suspensa. Entre em contato com o suporte para mais informações.'
                : 'A denúncia contra você está em análise. Recomendamos aguardar a resposta do suporte.';

            try {
                Mail::to($emailContratado)->send(new DenunciaTratadaMail($msgContratado, $assunto));
            } catch (\Exception $e) {
                Log::error("Erro ao enviar e-mail para o contratado: {$e->getMessage()}");
            }
        }

        // Retorno de sucesso
        return response()->json(['message' => "Denúncia atualizada para \"$acao\" com sucesso!"], 200);
    }



    public function toggleSuspensionFromDenuncia($id)
    {
        $denuncia = Denuncia::with('contratado')->findOrFail($id);

        // Obtém o profissional relacionado à denúncia
        $profissional = $denuncia->contratado;

        if ($profissional) {
            // Alterna o estado de suspensão
            $profissional->is_suspended = !$profissional->is_suspended;
            $profissional->save();

            // Mensagem de feedback
            $status = $profissional->is_suspended ? 'suspenso' : 'ativado';

            // Adiciona log opcional
            Log::info("Profissional {$profissional->idContratado} foi {$status} via denúncia {$denuncia->id}.");

            return redirect()->route('atendimento')->with('success', "Profissional {$status} com sucesso!");
        }

        return redirect()->route('atendimento')->with('error', 'Profissional associado à denúncia não encontrado.');
    }


    public function reativarContaPro($idContratado)
    {
        $profissional = Profissional::findOrFail($idContratado);
        $profissional->is_suspend = false; // Reativa a conta
        $profissional->save();

        $mensagem = "Sua conta foi reativada com sucesso.";
        $assunto = "Reativação de Conta";

        try {
            Mail::to($profissional->emailContratado)->send(new DenunciaTratadaMail($mensagem, $assunto));
        } catch (\Exception $e) {
            Log::error("Erro ao enviar e-mail de reativação: {$e->getMessage()}");
        }

        return response()->json(['message' => 'Conta reativada com sucesso e e-mail enviado.'], 200);
    }



        public function mostrarDenunciasEmAnalise (){



            $denuncias = Denuncia::with([
                'profissional' => function ($query){
                    $query->select('nomeContratado', 'emailContratado','cpfContratado');
                },
                'contratatante'=> function ($query){
                    $query->select('nomeContratante', 'emailContratante','cpfContratante');
                }
            ])
                ->where('status', 'emAnalise')
                ->get();

            if ($denuncias->isEmpty()) {
                    return response()->json(['message' => 'Nenhum pedido foi realizado a você']);
                }

                return response()->json($denuncias);

        }



        public function send(Request $request, $id)
        {
            Log::info('Iniciando o método send.', ['id' => $id, 'request' => $request->all()]);

            // Valida os dados recebidos
            $validated = $request->validate([
                'acao' => 'required|string|in:emAnalise,concluido',
                'motivo' => 'nullable|string|max:255', // Motivo é opcional
                'suspender_profissional' => 'nullable|boolean', // Campo opcional para suspensão
            ]);

            $acao = $validated['acao'];
            Log::info('Validação concluída.', ['validated' => $validated]);

            // Obtém a denúncia correspondente
            $denuncia = Denuncia::with(['contratante', 'contratado'])->findOrFail($id);
            Log::info('Denúncia encontrada.', ['denuncia' => $denuncia]);

            // Determina os parâmetros do e-mail
            $toEmail = $denuncia->contratante->emailContratante; // E-mail do contratante
            $motivo = $validated['motivo'] ?? 'Os fatos estão sendo apurados, por favor aguarde.';
            $subject = 'Atualização da Denúncia'; // Assunto do e-mail
            $mensagem = '';

            try {
                if ($acao === 'emAnalise') {
                    $mensagem = "Olá, {$denuncia->contratante->nomeContratante}. A denúncia foi recebida e está sendo analisada. {$motivo}";

                    // Atualiza o status da denúncia para "em análise"
                    $denuncia->status = 'emAnalise';
                    $denuncia->save();
                    Log::info('Denúncia atualizada para em análise.', ['id' => $id]);

                } elseif ($acao === 'concluido') {
                    $mensagem = "Olá, {$denuncia->contratante->nomeContratante}. A análise da denúncia foi concluída. {$motivo}";

                    // Atualiza o status da denúncia para "concluído"
                    $denuncia->status = 'concluido';
                    $denuncia->save();
                    Log::info('Denúncia atualizada para concluído.', ['id' => $id]);

                    // Suspende o profissional associado, se aplicável
                    if ($request->has('suspender_profissional') && $request->suspender_profissional && $denuncia->contratado) {
                        $contratado = $denuncia->contratado;
                        $contratado->status = 'suspenso'; // Define o status como suspenso
                        $contratado->save();
                        Log::info('Profissional suspenso.', ['profissional_id' => $contratado->id]);
                    }
                }

                // Envia o e-mail
                Mail::to($toEmail)->send(new DenunciaTratadaMail($mensagem, $subject));
                session()->flash('success', 'E-mail enviado com sucesso!');
                Log::info('E-mail enviado com sucesso.', ['email' => $toEmail]);

            } catch (\Exception $e) {
                // Define uma mensagem de erro na sessão e registra o log
                session()->flash('error', 'Erro ao processar ação: ' . $e->getMessage());
                Log::error('Erro ao processar ação.', ['error' => $e->getMessage()]);
            }

            // Redireciona para a tela de atendimentos
            return redirect()->route('atendimento');
        }



}
