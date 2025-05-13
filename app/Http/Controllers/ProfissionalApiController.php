<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profissional;
use Pusher\Pusher;
use Carbon\Carbon;



class ProfissionalApiController extends Controller
{

    public function indexApiPro()
    {

        $profissionais = Profissional::all();
        return $profissionais;

    }

    public function storeApiPro(Request $request)
    {

        // Validação dos campos recebidos no request
        $validadeDataPro = $request->validate([
            'nomeContratado' => 'required|string',
            'sobrenomeContratado' => 'required|string',
            'cpfContratado' => 'required|string',
            'password' => 'required|string',
            'emailContratado' => 'required|email|string',
            'profissaoContratado' => 'required|string',
            'telefoneContratado' => 'required|string',
            'descContratado' => 'nullable|string',
            'nascContratado' => 'required|date_format:d/m/Y',
            'ruaContratado' => 'required|string',
            'cepContratado' => 'required|string',
            'bairroContratado' => 'required|string',
            'regiaoContratado' => 'required|string',
            'numCasaContratado' => 'required|string',
            'complementoContratado' => 'nullable|string',
            'cidadeContratado'=>"required|string",
            'imagemContratado'=>"required|string",
            'portifilioPro1'=>"nullable|string",
            'portifilioPro2'=>"nullable|string",
            'portifilioPro3'=>"nullable|string",
        ]);

        $validadeDataPro['nascContratado'] = Carbon::createFromFormat('d/m/Y', $validadeDataPro['nascContratado'])->format('Y-m-d');

        $existingPro = Profissional::where('emailContratado', $validadeDataPro['emailContratado'])->first();



        if ($existingPro) {
            return response()->json([
                'status' => 'Falha',
                'message' => 'Algum profissional já foi cadastrado com este e-mail.'
            ], 409); // 409 Conflict
        }


        $validadeDataPro['password'] = bcrypt($validadeDataPro['password']);


        $profissional = Profissional::create($validadeDataPro);


        return response()->json([
            'status' => 'Cadastro realizado com sucesso',
            'alert' => 'Cadastro realizado com sucesso!',
            'data' => $profissional
        ], 201); // 201 Created

    }


    public function update(Request $request, $idContratado){
        $request->validate([
            'telefoneContratado'=>'required|numeric|digits:11',
            'emailContratado'=>'required|email|max:255',
            'cepContratado'=>'required|numeric|digits:8',
        ]);

        try{
            $pro = Profissional::findOrFail($idContratado);
            $pro -> telefoneContratado = $request->telefoneContratado;
            $pro -> emailContratado = $request->emailContratado;
            $pro -> cepContratado = $request->cepContratado;
            $pro->save();
            return response()->json([
                'message' => 'Contratado atualizado com sucesso!',
                'contratante' => $pro
            ], 200);

        }catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao atualizar o usuário: ' . $e->getMessage());
    }
}


    // Método para listar dados do profissional
    public function dadosProfissional()
    {
        try {
            $profissionalId = Auth::user()->idContratado;

            // Verifica se o profissional está autenticado
            if (!$profissionalId) {
                return response()->json(['error' => 'Profissional não autenticado'], 401);
            }

            // Busca os dados do profissional e leva para tela de perfil
            $profissional = Profissional::select('idContratado', 'nomeContratado', 'sobrenomeContratado', 'descContratado','profissaoContratado','bairroContratado')
                ->where('idContratado', $profissionalId) // Use o idContratado da autenticação
                ->get();

            return response()->json($profissional); // Retorna os dados do profissional
        } catch (\Exception $e) {
            // Retorna um erro caso algo ocorra
            return response()->json(['error' => 'Erro ao trazer os dados: ' . $e->getMessage()], 500);
        }
    }
    public function showApi($id)
    {
        $profissional = Profissional::find($id);
        if ($profissional) {
            return response()->json($profissional, 200);
        } else {
            return response()->json(['message' => 'Contratante não encontrado.'], 404);
        }
    }


    public function authPro(Request $request)
    {
        $validador = [
            'emailContratado' => 'required|email',
            'password' => 'required|string',
        ];

        $validacao = Validator::make($request->all(), $validador);

        if ( $validacao->fails()){
            return response()->json( [
                'status'=> 'Falha ao validar o cadastro',
                'message' => $validacao->errors()->all(),
            ]);
        }

        $credenciais= [
            'emailContratado' => $request->input('emailContratado'),
            'password' => $request->input('password'),
        ];


        if (!Auth::guard('profissional')->attempt($credenciais)) {
            return response()->json([
                'status' => 'Falha',
                'message' => 'Login incorreto, tente novamente',
            ]);
        }

        $userPro = Auth::guard('profissional')->user();
        $token = $userPro->createToken('contratado_token')->plainTextToken;
        // $pusherAuthData = $this->authorizePusher($userPro);

        if ($userPro->is_suspended) {
            // Desloga imediatamente se a conta estiver suspensa
            Auth::logout();
            return response()->json(['error' => 'Sua conta está suspensa. Entre em contato com o suporte.'], 403);
        }

        return response()->json([
            'status' => 'Sucesso',
            'message' => 'Seja bem-vindo' .  $userPro->nomeContratado,
            'token' => $token,
            'user'=>$userPro,
            // 'pusher_auth' => $pusherAuthData,
        ]);
    }

    protected function authorizePusher($userPro)
    {
        // Aqui você pode definir os dados necessários para autenticação do Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER'), 'useTLS' => true]
        );

        $channelName = 'private-my-channel';
        $socketId = request('socket_id'); // Precisa ser enviado pelo front-end

        return $pusher->socket_auth($channelName, $socketId);
    }

}
 {/*
        // Método para exibir os dados do PRofissional
        public function dadosProfissionais(Request $request)
        {
            try {
                // Recupera o profissional autenticado
                $profissional['idContratado'] = Auth::user()->idContratado;


                // Verifica se o profissional está autenticado
                if (!$profissional) {
                    return response()->json(['error' => 'Profissional não autenticado'], 401);
                }


                // Busca os dados do profissinal e leva para tela de perfil
                $profissional = Profissional::select('idContratado', 'nomeContratado', 'sobrenomeContratado', 'descContratado','profissaoContratado','bairroContratado')
                ->where('idContratado', $profissional) // Use o idContratado da autenticação
                //->where('statusPedido', 'pendente') // Verifique se o status é 'pendente'
                ->get();

                // Retorna os profissionais em formato JSON
                return response()->json($profissional);
            } catch (\Exception $e) {
                // Retorna um erro caso algo ocorra
                return response()->json(['error' => 'Erro ao trazer dados do PRO: ' . $e->getMessage()], 500);
            }
        }
*/}
