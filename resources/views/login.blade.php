

<form action="{{ route('login.store') }}" method="POST">
    @csrf
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<div class="wrapper">
    <!-- Container principal -->
    <div class="container main">
        <!-- Linha para distribuição de conteúdo -->
        <div class="row">
            <!-- Coluna para o formulário de login -->
            <div class="principal left">
                <div class="input-box">
                    <!-- Cabeçalho do formulário -->
                    <header>Login</header>
                    <!-- Campo de entrada para o email -->
                    <div class="input-field">
                        <input type="email" name="email" class="input" id="email" required="" autocomplete="on">
                        <label for="email"><i class="fa-solid fa-envelope"></i>E-mail</label>
                    </div>
                    <!-- Campo de entrada para a senha -->
                    <div class="input-field">
                        <input type="password" name="password" class="input" id="pass" required="">
                        <label for="pass"><i class="fa-solid fa-lock"></i> Senha</label>
                    </div>
                    <!-- Botão de envio do formulário -->
                    <div class="input-field">
                        <input type="submit" class="submit" value="Entrar">
                    </div>
                    <!-- Link para o cadastro -->
                    <div class="signin">
                        <span>Não tem uma conta? <a href="{{route('login.register')}}">Faça o cadastro aqui!</a></span>
                    </div>
                        @if($mensagem = Session::get('err'))
                            {{$mensagem}}
                        @endif
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                {{ $error }} <br>
                            @endforeach
                        @endif
                </div>
            
            <!-- Coluna para a imagem lateral -->
            <div class="ladinho1 " id="side-image">
                
                <!-- Adicione o texto sobre a imagem aqui se necessário -->
               <div class="ladinho"><img src="{{ asset('img/logohh.svg')}}" alt=""></div>
            </div>

            </div>
        </div>
    </div>
</div>
</form>
