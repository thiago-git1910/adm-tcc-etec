<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Avaliacao;
use App\Models\Contratante;



class AvaliacaoController extends Controller
{
    // Método que retorna todas as avaliações (pode ser mantido ou não)
    public function index()
    {
        $avaliacao = Avaliacao::all();
        return $avaliacao;
    }

    // Método para criar uma nova avaliação
    public function store(Request $request)
    {
        // Valida os dados de entrada
        $validated = $request->validate([
            'idContratado' => 'required|string',
            'idContratante' => 'required|string',
            'ratingAvaliacao' => 'required|integer|min:1|max:5',
            'descavaliacao' => 'nullable|string|max:255',
            'imagem' => 'required|string',
            'nome' => 'required|string',
        ]);

        try {
            // Verifica se já existe uma avaliação do contratante para o contratado
            $avaliacaoExistente = Avaliacao::where('idContratado', $validated['idContratado'])
                ->where('idContratante', $validated['idContratante'])
                ->first();

            if ($avaliacaoExistente) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Você já avaliou este profissional.',
                ], 400);
            }

            // Cria a avaliação
            $avaliacao = Avaliacao::create([
                'idContratado' => $validated['idContratado'],
                'idContratante' => $validated['idContratante'],
                'ratingAvaliacao' => $validated['ratingAvaliacao'],
                'nome' => $validated['nome'] ?? null,
                'imagem' => $validated['imagem'] ?? null,
                'descavaliacao' => $validated['descavaliacao'] ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'avaliacao' => $avaliacao,
            ], 201);

        } catch (\Exception $e) {
            // Loga o erro em caso de falha
            Log::error("Erro ao salvar avaliação: " . $e);
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao salvar a avaliação.',
            ], 500);
        }
    }




public function getAvaliacoesByContratado($idContratado)
{
    try {
        // Busca as avaliações pelo id do contratado e inclui os dados do contratante
        $avaliacoes = Avaliacao::where('idContratado', $idContratado)
            ->with('contratante') // Garante que traz os dados do contratante associado
            ->get();

        return response()->json([
            'status' => 'success',
            'avaliacoes' => $avaliacoes,
        ], 200);

    } catch (\Exception $e) {
        Log::error("Erro ao buscar avaliações: " . $e);
        return response()->json([
            'status' => 'error',
            'message' => 'Falha ao buscar avaliações.',
        ], 500);
    }
}


}
