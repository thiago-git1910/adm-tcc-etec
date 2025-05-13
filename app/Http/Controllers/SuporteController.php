<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use Illuminate\Http\Request;
use App\Models\Profissional;
use App\Models\Contratante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\DenunciaTratadaMail;
use Illuminate\Support\Facades\Mail;
class SuporteController extends Controller
{

    public function store (Request $request){


        $validacao = $request -> validate( [
            'descricao'=>'required|string|max:300',
            'idContratado'=> 'required|uuid',
            'idContratante'=>'required|uuid',
            'categoria'=>'required|string',
            'status'=>'emAnalise',
            'imagemDenuncia'=>"nullable|string",
        ]);

        try{
            $idContratante = Auth::user()->idContratante;
            if (!$idContratante) {
                return response()->json(['error' => 'Usuário não autenticado ou sem permissão.'], 403);
            }



            $denuncia = Denuncia::create([
                'descricao' => $request->descricao,
                'idContratado' => $request->idContratado,
                'idContratante' => $idContratante,
                'categoria' => $request->categoria,
                'status' => 'emAnalise',
            ]);

            $profissional = Profissional::findOrFail($request->idContratado);

            return response()->json([
                'message'=>'Denuncia realizada',
                'deuncia'=>$denuncia,
                'profissional'=>$profissional->nomeContratado,

            ], 201);
        }catch (\Exception $e) {
            $erro = $e;
            Log::info("Mensagem criada: " . $erro);
            return response()->json(['error' => 'Erro ao criar o pedido: ' . $e->getMessage()], 500);
        }
    }


    public function mostrarDenunciasEmAberto (){

        if (!Auth::user()->isUser()) {
            return response()->json(['error' => 'Acesso não autorizado.'], 403);
        }

        $denuncias = Denuncia::with([
            'profissional' => function ($query){
                $query->select('nomeContratado', 'emailContratado');
            },
            'contratatante'=> function ($query){
                $query->select('nomeContratante', 'emailContratante');
            }
        ])
            ->where('status', 'emAberto')
            ->get();

        if ($denuncias->isEmpty()) {
                return response()->json(['message' => 'Nenhum pedido foi realizado a você']);
            }

            return response()->json($denuncias);
    }

    public function mostrarDenunciasEmAnalise (){

        if (!Auth::user()->isUser()) {
            return response()->json(['error' => 'Acesso não autorizado.'], 403);
        }

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

    public function suspenderContaPro($idContratado){

        $profissional = Profissional::findOrFail($idContratado);
        $profissional -> is_suspend = true;
        $profissional->save();
        
        $mensagem = 'Sua conta foi suspensa devido a uma denúncia em análise. Entre em contato com o suporte para mais informações.';
        Mail::to($profissional->emailContratado)->send(new DenunciaTratadaMail($mensagem));

        return response()->json(['message' => 'A conta foi suspensa com sucesso e o e-mail foi enviado.']);

    }


}
