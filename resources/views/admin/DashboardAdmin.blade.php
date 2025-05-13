@extends('layouts.main')

@section('title', 'Dashboard')

@section('contentAdmin')

<div class="main p-3">
<div class="backdrop">
    <link rel="stylesheet" href="{{ asset('css/homeAdmin.css') }}">

{{-- Aqui ele busca o usuario logado -> name para apenas pegar o nome --}}
<div class="inicio">
<div class="header mb-4 ">
    <h3>Olá,<span style="color: #ff6347; ">{{$user->name}}</span></h3>
</div>
</div>

    <div class="title">
        <p class="titleDashboard">Dashboard</p>
    </div>

    <div class="pai">
        <div class="retanguloPro">
            <div class="vtnc">
            <i class="icon fas fa-hard-hat"></i> <!-- Icon for 'Pro' -->
            <span class="labelP">⠀Pro</span>
            </div>
            <span class="urus">|</span>
            <span class="countP">{{ $acountContratados ?? 18 }}</span> <!-- Example default value 18 -->
        </div>


        <div class="retanguloCli">

            <div class="mckevin">
                <i class="icon1 fas fa-user"></i> <!-- Icon for 'Client' -->
                <span class="labelC">⠀Cli</span>
            </div>
            <span class="uracan">|</span>
            <span class="countC">{{ $acountContratantes ?? 9 }}</span>
        </div>


        <div class="retanguloServ">
        <div class="mcig">
            <i class="icon fas fa-briefcase"></i> <!-- Icon for 'Services' -->
            <span class="labelS">⠀Serviços</span>
        </div>
            <span class="mccan">|</span>
            <span class="countS">{{ $contadorServicos ?? 15 }}</span>
        </div>

        <div class="retanguloZona">
        <div class="mcgp">
            <i class="icon fas fa-map-marker-alt"></i> <!-- Icon for 'Zone' -->
            <span class="labelZ">⠀Zona</span>
        </div>
            <span class="mccan">|</span>
            <span class="countZ">{{ $zone ?? 'ZL' }}</span>
        </div>

    </div>

    <hr>

    <div class="principal">
        <div class="partes">
            <h2 style="color: #004AAD">Serviços mais procurados</h2>
            <div class="grafico">
                <canvas id="chart" style="width: 370px; height: 370px;">
                    <!DOCTYPE html>
                    <html lang="en">
                    <head class= "grafico">
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Dashboard</title>
                        <!-- Adicione o Chart.js -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    </head>
                    <body>

                    <div class="chart-container" style="width: 50%; margin: auto;">
                        <canvas id="chart"></canvas>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const ctx = document.getElementById('chart').getContext('2d');

                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: {!! json_encode($labels) !!}, // Passando as labels dinâmicas
                                    datasets: [{
                                        label: 'Quantidade de Pedidos por Serviço',
                                        data: {!! json_encode($data) !!}, // Passando os dados dinâmicos
                                        backgroundColor: [
                                            '#FFA500', // Cor para Pedreiro
                                            '#3DAEDB', // Cor para Eletricista
                                            '#6BCFCF', // Cor para Encanador
                                            '#2D5186', // Cor para Montador
                                            '#FF6384', // Cor adicional para outro serviço
                                            '#36A2EB', // Cor adicional para outro serviço
                                            '#FFCE56'  // Cor adicional para outro serviço
                                        ],
                                        borderColor: [
                                            '#FFFFFF',
                                            '#FFFFFF',
                                            '#FFFFFF',
                                            '#FFFFFF',
                                            '#FFFFFF',
                                            '#FFFFFF',
                                            '#FFFFFF'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let label = context.label || '';
                                                    let value = context.raw || 0;
                                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                    let percentage = ((value / total) * 100).toFixed(1);
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>



                </canvas>

            </div>
            <div class="servicos">
                <div class="oncinha">
                    <h3>Serviços <span style="color:#f1c100 ">Solicitados</span></h3>
                    <h3 class="numero" style="color:#f1c100">{{$contadorPedidos}}</h3>
                </div>
                <div class="oncinha">
                    <h3>Serviços <span style="color:#009245">Concluidos</span> </h3>
                    <h3 class="numero" style="color:#009245">{{$concluidosCount}}</h3>
                </div>
            </div>
        </div>



        <div class="partes">
        <h2 style="color: #004AAD">Sobre usuários e serviços</h2>


        
                        <div class="avaliacoes">
                            <p style="color: #fff">Média de avaliações: 4.7 <i class= "icon fa fa-star"></i></p>
                        </div>



                        <div class="graficopro">
                        <div>
            <canvas id="mygrafico"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
            const ctx = document.getElementById('mygrafico');

            const labels = {!! json_encode($labelRegiao) !!};
            const data = {!! json_encode($dataNumRegiao) !!};

            new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels, // Corrigido para usar diretamente o array de labels
            datasets: [{
                label: 'Número de Profissionais por Região',
                data: data, // Corrigido para usar diretamente o array de dados
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)', // Cor para cada barra
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'x', // Altera o gráfico para colunas (se necessário)
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
            </script>
                            </div>


                            <div class="topPro">
                                <p>Top profissionais do mês</p>

                                @foreach ($topProfissionais as $index => $profissional)
                                    <div class="ranking"
                                        style="background-color: {{ $index == 0 ? '#fee267' : ($index == 1 ? '#ffeea1' : '#f9c397') }};">
                                        <div class="top{{ $index + 1 }}">
                                            <h2>{{ $index + 1 }}</h2>
                                            <div class="nomePro">
                                                <p style="color: white">{{ $profissional->nomeContratado }} |</p>
                                            </div>
                                            <div class="categoriaPro">
                                                <p style="color: #5e5e5e">{{ $profissional->profissaoContratado }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>





                    </div>



        <div class="partes1">
            <h2 style="color: #004AAD">Crescimento de usúarios</h2>


            <div class="grafico2">
    <canvas id="veigh" style="width: 500px; height: 280px;"></canvas>
                </div>



                <script>
       document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('veigh').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($cadastroMes) !!}, // Labels para os meses
            datasets: [
                {
                    label: 'Profissionais',
                    data: {!! json_encode($profissionalTotal) !!}, // Dados de profissionais
                    borderColor: '#004AAD',
                    backgroundColor: 'rgba(0, 74, 173, 0.2)',
                    borderWidth: 2
                },
                {
                    label: 'Clientes',
                    data: {!! json_encode($contratanteTotal) !!}, // Dados de clientes
                    borderColor: '#FF914D',
                    backgroundColor: 'rgba(255, 145, 77, 0.2)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});



                </script>

        <h2 style="color: #004AAD; margin-bottom: 3vh;">Funções diretas</h2>

            <div class="botoes">
                <a href="/users">
                <div class="usuarios">
                <div class="iconezinho">
                    <i class="fa-solid fa-user" style="color: #003ac2;"></i>
                </div>
                <div class="tituloaa">
                    <h2>Usuários</h2>
                </div>
                </div>
                </a>

                <a href="/add/servico">
                <div class="addservicos">
                <div class="iconezinho">
                    <i class="fa-solid fa-screwdriver-wrench" style="color: #0027c2;"></i>
                </div>
                <div class="tituloaa">
                    <h2>+ Serviços</h2>
                </div>
                </div>
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
